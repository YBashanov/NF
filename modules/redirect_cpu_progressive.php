<?php if ( ! defined('andromed')) exit(''); 

/*
упрощенный редирект
мы задаем конкретную точность для редиректа в файле config.php
2014-06-11 - небольшая доработка
*/
$inDB = false;
$redirect_type = "";

if ($seoTrue) {
	//формирование части строки из строки адреса - проверка для запуска редиректа
	$siteUri_forControl = "/";
	//формирование окончания строки из строки адреса - для подстановки в header
	$siteUri_tail = "";
	if ($site_uri_count > 0) {
		$request_uri = trim($request_uri, "/");
		$a_request_uri = explode("/", $request_uri);

		for($i=0; $i<$site_uri_count; $i++){
			if ($a_request_uri[$i]) {
				$siteUri_forControl .= $a_request_uri[$i] . "/";
			}
		}
		for($i=$site_uri_count; $i<count($a_request_uri); $i++){
			if ($a_request_uri[$i]) {
				$siteUri_tail .= $a_request_uri[$i] . "/";
			}
		}
	}
	else {
		$siteUri_forControl = $request_uri;
		$siteUri_tail = "";
	}

	if ($dbTrue) {
		$where = "NOT(`deleted`) AND `site_uri`='{$siteUri_forControl}' ORDER BY `priority` ASC";
		$seo = $db->select_line("{$config['prefix']}seo_lynks", $where, "*", "redirect_cpu_progressive");

		if (! $seo) {
			$where = "NOT(`deleted`) AND `site_uri`='{$request_uri}' ORDER BY `priority` ASC";
			$seo = $db->select_line("{$config['prefix']}seo_lynks", $where, "*", "redirect_cpu_progressive");
		}
	}

	if (! $seo) {
		//если редиректа не было (или был - но больше не будет),
		// смотрим, что это вообще за страница, и есть ли по ней информация в seo_lynks (тайтлы, кейвордс)
		
		//формирование части строки из строки адреса - проверка для запуска редиректа
		$siteRedirect_forControl = "/";
		//формирование окончания строки из строки адреса - для подстановки в header
		$siteRedirect_tail = "";

		if ($site_redirect_count > 0) {
			$request_uri = trim($request_uri, "/");
			$a_request_uri = explode("/", $request_uri);

			for($i=0; $i<$site_redirect_count; $i++){
				if ($a_request_uri[$i]) {
					if ($language != $a_request_uri[$i]) $siteRedirect_forControl .= $a_request_uri[$i] . "/";
				}
			}
			for($i=$site_redirect_count; $i<count($a_request_uri); $i++){
				if ($a_request_uri[$i]) {
					$siteRedirect_tail .= $a_request_uri[$i] . "/";
				}
			}
		}
		else {
			$siteRedirect_forControl = $request_uri;
			$siteRedirect_tail = "";
		}

		if ($dbTrue) {
			$where = "NOT(`deleted`) AND `site_redirect`='{$siteRedirect_forControl}' ORDER BY `priority` ASC";
			$seo = $db->select_line("{$config['prefix']}seo_lynks", $where, "*", "redirect_cpu_progressive");
		}
		$line->analisGet($seo[site_uri]);
		$get = $line->get;
		$s_get = $line->s_get;
		
		//нужен параметр, позволяющий заменять page на тот, что был до редиректа
		// (т.е. если мы хотим оставить page прежним, а страница нас переносит на другой page)
		if ($seo['is_replace_page'] == 1) {
			$page = $line->analisPage($seo[site_uri]);
		}

		if ($seo) {
			$inDB = true;//раньше, чем подгрузится d_common.php
			$redirect_type = "redirect";
			$data['title'] = $seo['title_'.$language];
			$data['keywords'] = $seo['keywords_'.$language];
			$data['description'] = $seo['description_'.$language];
		}
	}
	else {
		header ("Location: {$seo['site_redirect']}{$siteUri_tail}", '', 301);
		die();
	}
}
else{
	$line->analisGet();
	$get = $line->get;
	$s_get = $line->s_get;
}

if ($page == "") $page = "index";
?>