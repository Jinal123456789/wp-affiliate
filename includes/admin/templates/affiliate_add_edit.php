<?php
global $wpdb;
$qry = "SELECT * FROM " . $wpdb->prefix . "wpam_affiliates where id='" . $_GET['id'] ."'";
$row = $wpdb->get_row( $qry );
?>
<?php if (defined('wpam_message')) { ?>
    <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
        <p><strong><?php echo wpam_message; ?></strong></p>
    </div>
<?php } ?>
<br>
<h1 class="afltadeditttl">Affiliate Detail</h1>
<div class="postbox afltadedit" style="display: inline-block; padding: 0 20px; min-width: 60%">
<div class="postbox-header">
    <h2><?php echo ucwords($_GET['action']); ?> Affiliate Information</h2>
</div>
<form method="post">
    <input type="hidden" name="wpam_action" value="save_affiliate">
    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
    <table class="form-table wpam_table wpam_basic">
        <tr>
            <th><label>Company</label></th>
            <td><input type="text" name="company" value="<?php echo $row->company ?? ''; ?>" required></td>
        </tr>
        <tr>
            <th><label>Website</label></th>
            <td><input type="text" name="website" value="<?php echo $row->website ?? ''; ?>"></td>
        </tr>
        <tr>
            <th><label>Affiliate Login URL</label></th>
            <td><input type="text" name="login_url" value="<?php echo $row->login_url ?? ''; ?>"></td>
        </tr>
        <tr>
            <th><label>Login Username</label></th>
            <td><input type="text" name="login_username" value="<?php echo $row->login_username ?? ''; ?>"></td>
        </tr>
        <tr>
            <th><label>Commission % or $</label></th>
            <td><input type="text" name="commission" value="<?php echo $row->commission ?? ''; ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <hr>
                <input type="submit" value="Save Affiliate Information" class="button button-primary">
            </td>
        </tr>
    </table>

</form>
</div>