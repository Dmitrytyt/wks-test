<div class="users_list">
    <table class="data_table">
        <tr>
            <th>First name</th>
            <th>Last name</th>
            <th>login</th>
            <th>E-mail</th>
        </tr>
        <? if ($users): ?>
            <? foreach($users as $user): ?>
            <tr>
                <td><?php echo $user['first_name']; ?></td>
                <td><?php echo $user['last_name']; ?></td>
                <td><?php echo $user['login']; ?></td>
                <td><?php echo $user['email']; ?></td>
            </tr>
            <? endforeach; ?>
        <?else :?>
        <tr>
            <td colspan="5" align="center">No results</td>
        </tr>
        <?endif;?>
    </table>
</div>