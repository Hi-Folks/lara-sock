<?php

test('example', function () {
    expect(true)->toBeTrue();
});

test('openswoole loaded', function () {
    expect(
        extension_loaded('openswoole') ||
        extension_loaded('swoole')
    )->toBeTrue();
});
