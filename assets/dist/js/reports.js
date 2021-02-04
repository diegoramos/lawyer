$('.parent-list a').click(function(e){
	e.preventDefault();
	$('.parent-list a').removeClass('active');
	$(this).addClass('active');
	var currentClass='.child-list .'+ $(this).attr("id");
	$('.child-list .page-header').html($(this).html());
	$('.child-list .list-group').addClass('hidden');
	$(currentClass).removeClass('hidden');
$('#right_heading').addClass('active');
$('html, body').animate({
    scrollTop: $("#report_selection").offset().top
 }, 500);
});

$('.expand-collapse').click(function() {
	$('#options').slideToggle();
	$('#expand-collapse-icon').toggleClass('ion-chevron-up');
	$('#expand-collapse-icon').toggleClass('ion-chevron-down');
});

$('#generate_report').click(function(e){
     e.preventDefault();
     $('#options').slideToggle(function() {
     	$('#report_input_form').submit();
     });
 });


 $(function () {
 	$("select[name=report_date_range_simple]").change(function(event) {
 		if ($(this).val()=='CUSTOM') {
 			$("#report_date_range_complex").removeClass('hidden');
 		}else{
 			$("#report_date_range_complex").addClass('hidden');
 		}
 	});


    var sd = new Date(), ed = new Date();
  /*
    $('#start_date_formatted').datetimepicker({ 
      //pickTime: false, 
      format: "YYYY/MM/DD", 
      defaultDate: sd, 
      maxDate: ed 
    });
  
    $('#end_date_formatted').datetimepicker({ 
      //pickTime: false, 
      format: "YYYY/MM/DD", 
      defaultDate: ed, 
      minDate: sd 
    });*/


 $('.start-date').datepicker({
  templates: {
    leftArrow: '<i class="fa fa-chevron-left"></i>',
    rightArrow: '<i class="fa fa-chevron-right"></i>'
  },
  format: "dd/mm/yyyy",
  //startDate: new Date(),
  keyboardNavigation: false,
  autoclose: true,
  todayHighlight: true,
  disableTouchKeyboard: true,
  orientation: "auto"
});

$('.end-date').datepicker({
  templates: {
    leftArrow: '<i class="fa fa-chevron-left"></i>',
    rightArrow: '<i class="fa fa-chevron-right"></i>'
  },
  format: "dd/mm/yyyy",
  startDate: moment().add(1, 'days').toDate(),
  keyboardNavigation: false,
  autoclose: true,
  todayHighlight: true,
  disableTouchKeyboard: true,
  orientation: "auto"

});


$('.start-date').datepicker().on("changeDate", function () {
  var startDate = $('.start-date').datepicker('getDate');
  var oneDayFromStartDate = moment(startDate).add(1, 'days').toDate();
  $('.end-date').datepicker('setStartDate', oneDayFromStartDate);
  $('.end-date').datepicker('setDate', oneDayFromStartDate);
});

$('.end-date').datepicker().on("show", function () {
  var startDate = $('.start-date').datepicker('getDate');
  $('.day.disabled').filter(function (index) {
    return $(this).text() === moment(startDate).format('D');
  }).addClass('active');
});

});