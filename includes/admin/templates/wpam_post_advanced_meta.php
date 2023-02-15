<?php $wpam_meta_advanced_val = get_post_meta($post->ID); ?>
<table class="form-table  wpam_table wpam_advanced">
  <tbody>
    <tr>
        <th>No Follow 
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>No Follow</h3>
                    <p class="magnet-data-info"><?php echo notes[4]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td>
            <label class="switch">
                <input type="checkbox" name="wpam_nofollow" <?php if( isset ( $wpam_meta_advanced_val['wpam_nofollow']) && $wpam_meta_advanced_val['wpam_nofollow'][0] == "on"){ echo "checked";} ?>>
                <span class="switch_slider"></span>
            </label>
        </td>
    </tr>
    <tr>
        <th>Sponsored  
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Sponsored</h3>
                    <p class="magnet-data-info"><?php echo notes[5]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td>
            <label class="switch">
                <input type="checkbox" name="wpam_sponsored" <?php if( isset ( $wpam_meta_advanced_val['wpam_sponsored']) && $wpam_meta_advanced_val['wpam_sponsored'][0] == "on"){ echo "checked";} ?>>
                <span class="switch_slider"></span>
            </label>
        </td>
    </tr>
    <tr style="display:none;">
        <th>Tracking  
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Enable Tracking</h3>
                    <p class="magnet-data-info"><?php echo notes[4]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <!--td><input type="checkbox" name="wpam_tracking" <?php if( isset ( $wpam_meta_advanced_val['wpam_tracking']) && $wpam_meta_advanced_val['wpam_tracking'][0] == "on"){ echo "checked";} ?>></td-->
        <td><input type="checkbox" name="wpam_tracking" checked></td>
    </tr>
    <tr style="display:none;">
        <th>Expire  
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Expire</h3>
                    <p class="magnet-data-info"><?php echo notes[4]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <!--td><input type="checkbox" name="wpam_expire" <?php if( isset ( $wpam_meta_advanced_val['wpam_expire']) && $wpam_meta_advanced_val['wpam_expire'][0] == "on"){ echo "checked";} ?>></td-->
        <td><input type="checkbox" name="wpam_expire" checked></td>
    </tr>
    <tr>
        
        <th>Expire After Clicks  
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Expire After Clicks</h3>
                    <p class="magnet-data-info"><?php echo notes[6]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td><input type="text" name="wpam_expire_number" value='<?php if( isset ( $wpam_meta_advanced_val['wpam_expire_number']) ){ echo $wpam_meta_advanced_val['wpam_expire_number'][0];} ?>'></td>
    </tr>
    <tr>
        <th>Expire Redirect  
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Expire Redirect</h3>
                    <p class="magnet-data-info"><?php echo notes[7]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td><input type="checkbox" name="wpam_expire_redirect" <?php if( isset ( $wpam_meta_advanced_val['wpam_expire_redirect']) && $wpam_meta_advanced_val['wpam_expire_redirect'][0] == "on"){ echo "checked";} ?>></td>
    </tr>
    <tr>
        <th>Expire Redirect URL 
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Expire Redirect URL</h3>
                    <p class="magnet-data-info"><?php echo notes[8]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td><input type="url" name="wpam_expire_redirect_url" value='<?php if( isset ( $wpam_meta_advanced_val['wpam_expire_redirect_url'])){ echo $wpam_meta_advanced_val['wpam_expire_redirect_url'][0];} ?>'></td>
    </tr>
    <tr>
        <th>Notes 
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Notes</h3>
                    <p class="magnet-data-info"><?php echo notes[3]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td><input type="text" name="wpam-meta-note"
        value="<?php if( isset ( $wpam_meta_val['wpam-meta-note'])) echo $wpam_meta_val['wpam-meta-note'][0]; ?>" ></td>
    </tr>
    <tr>
        <th>Link to words or phrases 
            <span class="magnet-tooltip">
                <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                <span class="magnet-content">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <h3>Link to words or phrases</h3>
                    <p class="magnet-data-info"><?php echo notes[9]; ?></p>
                    <p class="close">Close</p>
                </span>
            </span>
        </th>
        <td><input type="text" name="wpam_expire_keywords" value='<?php if( isset ( $wpam_meta_advanced_val['wpam_expire_keywords'])){ echo $wpam_meta_advanced_val['wpam_expire_keywords'][0];} ?>'></td>
    </tr>
  </tbody>
</table>