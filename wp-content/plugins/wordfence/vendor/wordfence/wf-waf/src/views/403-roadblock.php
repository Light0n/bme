<?php

/** @var wfWAF $waf */
/** @var wfWAFView $this */

/*
 * IMPORTANT:
 * 
 * If the form variables below change name or format, admin.ajaxWatcher.js in the main plugin also needs changed. It
 * processes these to generate its whitelist button.
 */

$method = wfWAFUtils::strtolower($waf->getRequest()->getMethod());
$urlParamsToWhitelist = array();
foreach ($waf->getFailedRules() as $paramKey => $categories) {
	foreach ($categories as $category => $failedRules) {
		foreach ($failedRules as $failedRule) {
			/**
			 * @var wfWAFRule $rule
			 * @var wfWAFRuleComparisonFailure $failedComparison
			 */
			$rule = $failedRule['rule'];
			$failedComparison = $failedRule['failedComparison'];

			$urlParamsToWhitelist[] = array(
				'path'     => $waf->getRequest()->getPath(),
				'paramKey' => $failedComparison->getParamKey(),
				'ruleID'   => $rule->getRuleID(),
			);
		}
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>403 Forbidden</title>
</head>
<body>

<h1>403 Forbidden</h1>

<p>A potentially unsafe operation has been detected in your request to this site, and has been blocked by Wordfence.</p>

<?php if ($urlParamsToWhitelist): ?>
	<p>If you are an administrator and you are certain this is a false positive, you can automatically whitelist this
		request and repeat the same action.</p>

	<form id="whitelist-form" action="<?php echo htmlentities($waf->getRequest()->getPath(), ENT_QUOTES, 'utf-8') ?>"
	      method="post">
		<input type="hidden" name="wfwaf-false-positive-params"
		       value="<?php echo htmlentities(wfWAFUtils::json_encode($urlParamsToWhitelist), ENT_QUOTES, 'utf-8') ?>">
		<input type="hidden" name="wfwaf-false-positive-nonce"
		       value="<?php echo htmlentities($waf->getAuthCookieValue('nonce', ''), ENT_QUOTES, 'utf-8') ?>">

		<div id="whitelist-actions">
			<p>
				<label>
					<input id="verified-false-positive-checkbox" type="checkbox" name="wfwaf-false-positive-verified"
					       value="1">
					<em>I am certain this is a false positive.</em>
				</label>
			</p>

			<p>
				<button id="whitelist-button" type="submit">Whitelist This Action</button>
			</p>
		</div>

		<p id="success" style="color: #35b13a; font-weight: bold; display: none"><em>All set! You can refresh the page
				to try this action again.</em></p>

		<p id="error" style="color: #dd422c; font-weight: bold; display: none"><em>Something went wrong whitelisting
				this request. You can try setting the Firewall Status to Learning Mode under Web App Firewall in the
				Wordfence menu, and retry this same action.</em></p>
	</form>
	<script>
		var whitelistButton = document.getElementById('whitelist-button');
		var verified = document.getElementById('verified-false-positive-checkbox');
		verified.checked = false;
		verified.onclick = function() {
			whitelistButton.disabled = !this.checked;
		};
		verified.onclick();

		document.getElementById('whitelist-form').onsubmit = function(evt) {
			evt.preventDefault();
			var request = new XMLHttpRequest();
			request.addEventListener("load", function() {
				if (this.status === 200 && this.responseText.indexOf('Successfully whitelisted') > -1) {
					document.getElementById('whitelist-actions').style.display = 'none';
					document.getElementById('success').style.display = 'block';
				} else {
					document.getElementById('error').style.display = 'block';
				}
			});
			request.open("POST", this.action, true);
			request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			var inputs = this.querySelectorAll('input[name]');
			var data = '';
			for (var i = 0; i < inputs.length; i++) {
				data += encodeURIComponent(inputs[i].name) + '=' + encodeURIComponent(inputs[i].value) + '&';
			}
			request.send(data);
			return false;
		};


	</script>

<?php endif ?>

</body>
</html>