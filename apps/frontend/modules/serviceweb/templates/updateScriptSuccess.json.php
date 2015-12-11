<?php if(isset($error)): ?>
[
{
"error" : <?php echo $error; ?> ,
}
]
<?php else : ?>
<?php if(isset($result) && $result['success']):  ?>
[
{
"success" : <?php echo $result['message'] ?> ,
}
]
<?php else : ?>
[
{
"error" : "Process error . Update failed" ,
}
]
<?php endif; ?>

<?php endif; ?>