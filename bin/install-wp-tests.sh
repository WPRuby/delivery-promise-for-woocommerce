#!/usr/bin/env bash

set -euo pipefail

DB_NAME=${1-wordpress_test}
DB_USER=${2-root}
DB_PASS=${3-root}
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}
SKIP_DB_CREATE=${6-false}

WP_TESTS_DIR=${WP_TESTS_DIR-/tmp/wordpress-tests-lib}
WP_CORE_DIR=${WP_CORE_DIR-/tmp/wordpress}

if [ "$WP_VERSION" = 'latest' ]; then
	WP_TESTS_TAG='trunk'
else
	WP_TESTS_TAG="tags/$WP_VERSION"
fi

download() {
	local url=$1
	local dest=$2

	if command -v curl >/dev/null 2>&1; then
		curl -fsSL "$url" -o "$dest"
	elif command -v wget >/dev/null 2>&1; then
		wget -q -O "$dest" "$url"
	else
		echo "curl or wget is required." >&2
		exit 1
	fi
}

install_wp() {
	if [ -d "$WP_CORE_DIR/wp-includes" ]; then
		return
	fi

	mkdir -p "$WP_CORE_DIR"
	local archive
	archive="$(mktemp)"

	if [ "$WP_VERSION" = 'latest' ]; then
		download https://wordpress.org/latest.tar.gz "$archive"
	else
		download "https://wordpress.org/wordpress-${WP_VERSION}.tar.gz" "$archive"
	fi

	tar --strip-components=1 -zxmf "$archive" -C "$WP_CORE_DIR"
	rm -f "$archive"
}

install_test_suite() {
	if [ ! -d "$WP_TESTS_DIR/includes" ]; then
		mkdir -p "$WP_TESTS_DIR"
		svn export --quiet "https://develop.svn.wordpress.org/${WP_TESTS_TAG}/tests/phpunit/includes/" "$WP_TESTS_DIR/includes"
		svn export --quiet "https://develop.svn.wordpress.org/${WP_TESTS_TAG}/tests/phpunit/data/" "$WP_TESTS_DIR/data"
	fi

	if [ ! -f "$WP_TESTS_DIR/wp-tests-config.php" ]; then
		download "https://develop.svn.wordpress.org/${WP_TESTS_TAG}/wp-tests-config-sample.php" "$WP_TESTS_DIR/wp-tests-config.php"

		WP_CORE_DIR="$(echo "$WP_CORE_DIR" | sed 's:/\+$::')"
		sed -i.bak "s:dirname( __FILE__ ) . '/src/':'${WP_CORE_DIR}/':" "$WP_TESTS_DIR/wp-tests-config.php"
		sed -i.bak "s/youremptytestdbnamehere/$DB_NAME/" "$WP_TESTS_DIR/wp-tests-config.php"
		sed -i.bak "s/yourusernamehere/$DB_USER/" "$WP_TESTS_DIR/wp-tests-config.php"
		sed -i.bak "s/yourpasswordhere/$DB_PASS/" "$WP_TESTS_DIR/wp-tests-config.php"
		sed -i.bak "s|localhost|$DB_HOST|" "$WP_TESTS_DIR/wp-tests-config.php"
		rm -f "$WP_TESTS_DIR/wp-tests-config.php.bak"
	fi
}

create_db() {
	if [ "$SKIP_DB_CREATE" = 'true' ]; then
		return
	fi

	mysqladmin create "$DB_NAME" --user="$DB_USER" --password="$DB_PASS" --host="$DB_HOST" 2>/dev/null || true
}

install_wp
install_test_suite
create_db
