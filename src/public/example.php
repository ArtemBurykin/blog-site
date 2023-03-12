<?php
$name = 'Dave';
?>

<?php if ($name === 'Dave'): ?>
    <p>Hello <?php echo $name; ?></p>
<?php else: ?>
    <p> Go fuck yourself, <?php echo $name;?> </p>
<?php endif; ?>

<?php for($i = 0; $i < 5; $i ++): ?>
    <li><?= $i; ?></li>
<?php endfor; ?>
