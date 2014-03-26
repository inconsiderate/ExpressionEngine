<!doctype html>
<html>
	<head>
		<?=$this->view->head_title($cp_page_title)?>
		<?=$this->view->head_link('css/v3/login.css'); ?>
	</head>
	<body>
		<section class="wrap">

			<?=$EE_rendered_view?>

		</section>
		<section class="bar snap">
			<p class="left"><b>ExpressionEngine</b></p>
			<p class="right">&copy;2003&mdash;<?=ee()->localize->format_date('%Y')?> <a href="http://ellislab.com/expressionengine" rel="external">EllisLab</a>, Inc.</p>
		</section>
		<?=$this->view->script_tag('jquery/jquery.js')?>
		<?=$this->view->script_tag('v3/cmon-ck.js')?>
		<?=$this->view->script_tag('cp/v3/login.js')?>
		<script type="text/javascript">
			$(document).ready(function()
			{
				document.getElementById('<?=$focus_field?>').focus();
			});
		</script>
	</body>
</html>