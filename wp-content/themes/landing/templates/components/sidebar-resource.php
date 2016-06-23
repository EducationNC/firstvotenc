<div class="well">
  <h4>Search Resources</h4>
  <form class="form-inline" method="get" action="<?php the_permalink() ?>">
    <div class="form-group col-xs-9">
      <label class="hidden" for="k">Search</label>
      <input type="text" class="form-control" name="k" id="k" placeholder="Search by keyword" />
    </div>
    <button type="submit" class="btn col-xs-3">Go</button>
  </form>
  <div class="clearfix"></div>
</div>

<div class="well">
  <h4>Resource Categories</h4>
  <?php $terms = get_terms('resource-type'); ?>
  <ul class="nav nav-stacked has-padding">
    <?php foreach( $terms as $term) { ?>
    <li>
      <a href="<?php echo esc_url( get_term_link( $term, $term->taxonomy ) ); ?>"><?php echo $term->name; ?></a>
    </li>
    <?php } ?>
  </ul>
</div>

<?php if ( is_tax('resource-type') || is_single() ) { ?>
  <a class="back-all" href="/resource-center">Back to all</a>
<?php } ?>
