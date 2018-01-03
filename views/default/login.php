<?php
/* @var $this yii\web\View */
\geoffry304\authy\AuthyAsset::register($this);

?>
<div class="login-form">
    <form method="POST">
        <h3>Two-Factor Verification</h3>
        Token: <input id="authy-token" name="authy-token" type="text" value="" />
        <br />
        <a href="#" id="authy-help">help</a>
    </form>
    </div>
