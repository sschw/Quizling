{if isset($smarty.session.user_id)}
  {if isset($subpage)}
{include file="{$page}/{$subpage}.tpl"}
  {else}
<h1>{#txt_administration#}</h1>
<ul style="padding: 0;">
  <li class="adminNavigation"><a href="admin/table/categories">{#txt_categories#}</a></li>
  <li class="adminNavigation"><a href="admin/table/questions">{#txt_questions#}</a></li>
  <li class="adminNavigation"><a href="admin/table/highscore">{#txt_highscore#}</a></li>
</ul>
<p>{#txt_adminPanelInfos#}</p>
  {/if}
{else}
{include file="login.tpl"}
{/if}