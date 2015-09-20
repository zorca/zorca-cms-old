@ECHO OFF
SET BIN_TARGET=%~dp0/../css-crush/css-crush/bin/csscrush
php "%BIN_TARGET%" %*
