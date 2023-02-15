<?php
$wpam_meta_val=get_post_meta($post->ID);
if ( $errors = get_transient( 'settings_errors' ) ) {
    $message = '<div id="acme-message" class="error below-h2"><ul>';
    foreach ( $errors as $error ) {
        $message .= '<li>' . $error['message'] . '</li>';
    }
    $message .= '</ul></div><!-- #error -->';
    echo $message;
    delete_transient( 'settings_errors' );
}

?>
<table class="form-table wpam_table wpam_basic">
    <tbody>
    <?php if(isset($wpam_meta_val['wpam-meta-redirect'][0])) { ?>
        <tr>
            <th>Redirection Code* 
                <span class="magnet-tooltip">
                    <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <span class="magnet-content">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <h3>Redirection Code</h3>
                        <p class="magnet-data-info"><?php echo notes[0]; ?></p>
                        <p class="close">Close</p>
                    </span>
                </span>
            </th>
            <td>
                <select required id="redirect_type" name="wpam-meta-redirect">
                    <option value="307" <?php if($wpam_meta_val['wpam-meta-redirect'][0] == 307){echo "selected";}?>>307 (Temporary)&nbsp;</option>
                    <option value="302" <?php if($wpam_meta_val['wpam-meta-redirect'][0] == 302){echo "selected";}?>>302 (Temporary)&nbsp;</option>
                    <option value="301" <?php if($wpam_meta_val['wpam-meta-redirect'][0] == 301){echo "selected";}?>>301 (Permanent)&nbsp;</option>
                    <option value="cloaked" <?php if($wpam_meta_val['wpam-meta-redirect'][0] == "cloaked"){echo "selected";}?>>Cloaked&nbsp;</option>
                </select>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th>Affiliate URL* 
                <span class="magnet-tooltip">
                    <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <span class="magnet-content">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <h3>Affiliate URL</h3>
                        <p class="magnet-data-info"><?php echo notes[1]; ?></p>
                        <p class="close">Close</p>
                    </span>
                </span>
            </th>
            <td><input required type="url" name="wpam-meta-target"
            value="<?php if( isset ( $wpam_meta_val['wpam-meta-target'])) echo $wpam_meta_val['wpam-meta-target'][0]; ?>" class="full-width"></td>
        </tr>
        <tr>
            <th>New Link*  
                <span class="magnet-tooltip">
                    <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <span class="magnet-content">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <h3>New Link</h3>
                        <p class="magnet-data-info"><?php echo notes[2]; ?></p>
                        <p class="close">Close</p>
                    </span>
                </span>
            </th>
            <td><strong><?php echo get_site_url()."/";?></strong><input required type="text" name="wpam-meta-link"
            value="<?php if( isset ( $wpam_meta_val['wpam-meta-link'])) echo $wpam_meta_val['wpam-meta-link'][0]; ?>" ></td>
        </tr>
        
        <tr>
            <th>Enable New Link*  
                <span class="magnet-tooltip">
                    <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    <span class="magnet-content">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <h3>Enable New Link</h3>
                        <p class="magnet-data-info"><?php echo notes[10]; ?></p>
                        <p class="close">Close</p>
                    </span>
                </span>
            </th>
            <td>
                <label class="switch">
                    <input  type="checkbox" name="wpam_enable_link" <?php if( !isset( $wpam_meta_val['wpam_enable_link']) || $wpam_meta_val['wpam_enable_link'][0] == "on"){ echo "checked";} ?>>
                    <span class="switch_slider"></span>
                </label>
            </td>
        </tr>
    </tbody>
</table>