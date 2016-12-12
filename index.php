<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Postfix Calculator</title>
    <link rel="stylesheet" type="text/css" href="index.css" />
</head>
<body>
<div id="calc">
    <div class="top-row">
        <!-- calc screen -->
        <div id="screen">
            <?php
                include "calc.php";
            ?>
        </div>
    </div>
    <!-- calc keypad -->
    <div id="keys">
        <!-- calc keys-->
        <div id="clear">
            <span class="clear">C</span>
        </div>
        <!-- number key pad -->
        <div id="num-keys">
            <span>(</span>
            <span>)</span>
            <span class="operator">^</span>
            <span>7</span>
            <span>8</span>
            <span>9</span>
            <span class="operator">÷</span>
            <span>4</span>
            <span>5</span>
            <span>6</span>
            <span class="operator">x</span>
            <span>1</span>
            <span>2</span>
            <span>3</span>
            <span class="operator">−</span>
            <span>0</span>
            <span>.</span>
            <!-- eval keypad -->
            <div id="eval">
                <span class="eval">=</span>
            </div>
            <span class="operator">+</span>
        </div>
    </div>
</div>
</body>
</html>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax
/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
</script>
<script src="//code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript" src="index.js"></script>