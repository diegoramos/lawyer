
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.3.8
        </div>
        <strong>Copyright &copy; 2014-2018 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
        reserved.
      </footer>
      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="tab-pane" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading"></h3>
            <ul class="control-sidebar-menu">
            </ul>
            <!-- /.control-sidebar-menu -->
          </div>
          <!-- /.tab-pane -->
        </div>
      </aside>
      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.4.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery.print.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
<!--<script src="<?php echo base_url(); ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/plugins/moment/moment.js"></script>
<?php if ($this->uri->segment(1) == 'reports'): ?>
  <script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<?php endif?>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.js"></script>
<script src='<?php echo base_url(); ?>assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js'></script>
<script src='<?php echo base_url(); ?>assets/plugins/colorpicker/bootstrap-colorpicker.js'></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/print.min.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap validator -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
<!-- Fullcalendar -->
<script src="<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/fullcalendar/locale-all.js"></script>
<!-- Bootstrap-notify -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/lightbox/ekko-lightbox.min.js"></script>
<script type="text/javascript">
    $(document).ready(function ($) {
        // delegate calls to data-toggle="lightbox"
        $(document).on('click', '[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', function(event) {
            event.preventDefault();
            return $(this).ekkoLightbox({
                onShown: function() {
                    if (window.console) {
                        return console.log('Checking our the events huh?');
                    }
                },
                onNavigate: function(direction, itemIndex) {
                    if (window.console) {
                        return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                    }
                }
            });
        });
    });
</script>
<?php if ($this->uri->segment(2) == 'view'): ?>
<script src="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.js"></script>
<?php endif?>
<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
</script>

<!--        fin       -->
<?php if ($this->uri->segment(1) == 'customers'): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/customer.js"></script>
<?php endif?>
<?php if ($this->uri->segment(1) == 'user'): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/user.js"></script>
<?php endif?>
<?php if ($this->uri->segment(1) == 'lawyers'): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/lawyer.js"></script>
<?php endif?>
<?php if ($this->uri->segment(1) == 'calendar'): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/calendar.js"></script>
<?php endif?>

<?php if ($this->uri->segment(1) == 'reports'): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/reports.js"></script>
<?php endif?>

</body>
</html>
