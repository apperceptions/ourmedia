<?php

function _anders_footer()
{
	global $queries;
	global $timer;

	/*print '<!-- acl -->';
	return;*/

	$qtot = 0;
	$ptot = 0;
	if (variable_get('dev_timer', 0))
	{
  		list($usec, $sec) = explode(' ', microtime());
		$endt = (float)$usec + (float)$sec;
		$ptot = $endt-$timer;
		print '<div style="font-size:12px; font-style:italic; font-family:tahoma;">Total page time: '.$ptot.'s</div>';
	}
	if (variable_get('dev_query', 0))
	{
		function pred($a, $b)
		{
			if ($a[1] === $b[1]) return 0;
			return $a[1] < $b[1] ? -1 : 1;
		}
		usort($queries, 'pred');
														
		print '<table style="font-size:10px; font-family:tahoma;">'."\n";
		foreach ($queries as $q)
		{
			print '<tr><td valign="top">'.$q[1].'s</td><td>'.htmlentities($q[0]).'</td></tr>'."\n";
			$qtot += $q[1];
		}
		print '</table>'."\n";
	}
	if ($ptot != 0 && $qtot != 0)
		print '<div style="font-size:10px; font-family:tahoma;">'.count($queries).' Qs in '.$qtot.'s ('.(($qtot*100)/$ptot).'%)</div>';
}

if ($_SERVER['REMOTE_ADDR'] == '213.113.219.75') // '213.113.219.75'
{
	$conf['dev_query'] = 1;
	$conf['dev_timer'] = 1;
	$conf['cache'] = 0;
}

?>
