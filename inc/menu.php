<nav class="lcwo-menu">
	<a class="mLink" href="/"><? echo l('home') ?></a>
	<a class="mLink" href="/users"><? echo l('userlist') ?></a>
	<a class="mLink" href="/highscores"><? echo l('highscores') ?></a>
	<a class="mLink" href="/forum"><? echo l('forum')." ".privmsgcount();?></a>
	<a class="mLink" href="/usergroups"><? echo l('usergroups') ?></a>
	<a class="mLink" href="/about"><? echo l('about') ?></a>
	<? if ($_SESSION['uid']) { ?>
	<a id="logoutlink" class="mLink" href="/logout"><?
			echo l('logout')." (".$_SESSION['username']; ?>)</a>
	<? } ?>
</nav>
