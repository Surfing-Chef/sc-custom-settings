<h1>SC Custom Settings</h1>

<form method="POST">
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row">
          <label for="awesome_text">Google Map API</label>
        </th>
        <td>
          <input type="text" name="awesome_text" id="awesome_text" value="<?php echo $value; ?>" placeholder="Api Goes Here">
          <?php echo wp_nonce_field( 'wpshout_option_page_example_action' ); ?>
        </td>
      </tr>
    </tbody>
  </table>
  <p class="submit">
    <input type="submit" value="Save" class="button button-primary button-large">
  </p>
</form>
