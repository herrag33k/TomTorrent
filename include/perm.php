<?
// Unused at the moment
$pclass = $CURUSER['class'];

# Virkir notendur og ��ri
if($pclass >= UC_GOOD_USER) {
	define(PERM_VIEW_LOG, '1'); // View the log
}

# Allir innskr��ir
if($pclass >= UC_BEGINNER) {
	define(PERM_VIEW_FILES, '1'); // Browse torrents
	define(PERM_VIEW_PROFILE, '1'); // View one's profile
	define(PERM_CHANGE_PROFILE, '1'); // Change one's profile
}

?>
