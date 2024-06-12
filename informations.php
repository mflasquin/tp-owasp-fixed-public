<?php
session_start();
require_once('functions.php');
$user = null;
if(isset($_SESSION['user'])) {
    $users = getUser($_SESSION['user']->id);
    if(!empty($users)) {
        $user = $users[0];
    }
}
?>

<?php if($user): ?>
<h1>Information de l'utilisateur <?= $user->email ?></h1>
<table>
    <tr>
        <td>id</td>
        <td><?= htmlentities($user->id) ?></td>
    </tr>
    <tr>
        <td>username</td>
        <td><?= htmlentities($user->username) ?></td>
    </tr>
    <tr>
        <td>email</td>
        <td><?= htmlentities($user->email) ?></td>
    </tr>
</table>
<?php else: ?>
    L'utilisateur recherché n'existe pas
<?php endif; ?>