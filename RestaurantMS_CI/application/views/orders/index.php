

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('errors')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('errors'); ?>
          </div>
        <?php endif; ?>

        <?php if(in_array('createOrder', $user_permission)): ?>
          <a href="<?php echo base_url('orders/create') ?>" class="btn btn-success">Add Order</a>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Orders</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th>Bill no</th>
                <th>Store</th>
                <th>Date Time</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Paid status</th>
                <?php if(in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
              </thead>
              <tfoot>
                <tr>
                  <th colspan="5" style="text-align:right">Grand Total:</th>
                  <th></th>
                  <th colspan="2"></th>
                </tr>
              </tfoot>
            </table>
          </div>

          <!-- Display Total Orders and Total Amount 
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Order Summary</h3>
            </div>
            <div class="box-body">
              <p><strong>Total Orders:</strong> <?php echo $total_orders; ?></p>
              <p><strong>Total Amount:</strong> UGX <?php echo number_format($total_amount, 2); ?></p>
            </div>
          </div> -->

          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php if(in_array('deleteOrder', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Order</h4>
      </div>

      <form role="form" action="<?php echo base_url('orders/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          <button type="submit" class="btn btn-success">Yes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>


<script type="text/javascript">
var manageTable;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  $("#OrderMainNav").addClass('active');
  $("#manageOrderSubMenu").addClass('active');

  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': base_url + 'orders/fetchOrdersData',
    'order': [],
    'columns': [
      { 'data': 0 }, // Bill no
      { 'data': 1 }, // Store
      { 'data': 2 }, // Date Time
      { 'data': 3 }, // Product
      { 'data': 4 }, // Quantity
      {
        'data': 5,    // Total Amount (Net Amount)
        'render': function(data, type, row) {
          return 'UGX ' + number_format(data, 2);
        }
      },
      { 'data': 6 }, // Paid status
      <?php if(in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
      { 'data': 6 }  // Action
      <?php endif; ?>
    ],
    'footerCallback': function(row, data, start, end, display) {
      var api = this.api();

      // Remove the formatting to get numeric data for summation
      var intVal = function (i) {
        return typeof i === 'string' ?
          i.replace(/[^0-9\.-]+/g, '') * 1 :
          typeof i === 'number' ?
            i : 0;
      };

      // Total over this page (filtered data)
      var pageTotal = api
        .column(4, { page: 'current' })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(4).footer()).html('UGX ' + number_format(pageTotal, 2));
    }
  });

});

// Function to format numbers with commas and decimal points
function number_format(number, decimals) {
  var n = number, 
      c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
  var d = ".", t = ",";
  var s = n < 0 ? "-" : "";
  var i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c)));
  var j = (i.length > 3) ? i.length % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + 
    i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + 
    (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}
</script>
