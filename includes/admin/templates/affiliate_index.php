<?php
global $wpdb;
$qry = "SELECT * FROM " . $wpdb->prefix . "wpam_affiliates";
$rows = $wpdb->get_results( $qry );
// print_r($rows);
// var_dump($rows[0]->login_url);

?>
<?php if (defined('wpam_message')) { ?>
    <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
        <p><strong><?php echo wpam_message; ?></strong></p>
    </div>
<?php } ?>
<br>
<h1 class="afiliateh1">
    Affiliate Details &nbsp; &nbsp; 
    <a class="affiliatebtn" href="<?php echo AME()->ame_url . '&page=wpam_affiliate&action=add'; ?>" class="button">Add New</a>
</h1>
<?php if (defined('wpam_affiliate_message')) { ?>
    <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
        <p><strong><?php echo wpam_affiliate_message; ?></strong></p>
    </div>
<?php } ?>
<br>

<div class="cmntablewrp">
<table class="wp-list-table widefat cmntable" id="broken-link-table" cellspacing="0">
    <thead>
        <tr>
            <th class="affrdetail">Company</th>
            <th class="affrdetail">Website</th>
            <th class="affrdetail">Login Username</th>
            <th class="affrdetail">Login Username</th>
            <th class="affrdetail">Commission $ OR %</th>
            <th class="affrdetail">Go Now</th>
            <th class="affrdetail" style="min-width:220px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows) { foreach($rows as $row) { ?>
            <tr>
                <td class="text-center"><?php echo $row->company; ?></td>
                <td class="text-center"><?php echo $row->website; ?></td>
                <td class="text-center"><?php echo $row->login_url; ?></td>
                <td class="text-center"><?php echo $row->login_username; ?></td>
                <td class="text-center"><?php echo $row->commission; ?></td>
                <td class="text-center"> <a href="<?php echo $row->login_url;?>" class="button" target="_blank">Go Now</a> 
                <?php //echo $row->login_url;?>
                </td>
                <td class="text-center">

                    &nbsp;
                    <a href="<?php echo AME()->ame_url . '&page=wpam_affiliate&action=edit&id='.$row->id; ?>" class="button">Edit</a>
                    &nbsp;
                    <form method="post" style="display:inline-block;" onSubmit="if(!confirm('Are you Sure to Delete Record?')){return false;}">
                        <input type="hidden" name="wpam_action" value="delete_affiliate">
                        <input type="hidden" name="id" value="<?php echo $row->id;?>">
                        <input type="submit" value="Delete" class="button button-danger">
                    </form>
                </td>
            </tr>
        <?php } } ?>
    </tbody>
</table>
</div>