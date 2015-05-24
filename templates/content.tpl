{config_load file="config.conf"}
{config_load file="language_{$smarty.session.language}.conf"}
{include file="{$page}.tpl"}