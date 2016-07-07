<?php $page = get_posts(['name' => 'subscribe', 'post_type' => 'page']); ?>
<div class="modal fade email-signup-modal print-no" id="emailSignupModal" tabindex="-2" role="dialog" aria-labelledby="emailSignupModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php if ($page) { echo $page[0]->post_title; } ?></h4>
      </div>
      <div class="modal-body">
        <?php if ($page) { echo wpautop($page[0]->post_content); } ?>
        <script type="text/javascript" src="//app.icontact.com/icp/loadsignup.php/form.js?c=871299&l=6783&f=4460"></script>
      </div>
    </div>
  </div>
</div>
