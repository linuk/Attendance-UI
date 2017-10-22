<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Attendance System</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
	<meta name="description" content="Attendance UI for displaying working hours view">
	<meta name="keywords" content="HTML, CSS, PHP, AJAX, jQuery, Chart JS, Animation JS">
	<meta name="author" content="Jheng-Hao Lin">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script type='text/javascript' src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	
	<!-- Animation.css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

	<!-- Chart.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:100, 200,300,400" rel="stylesheet">
	<link rel="stylesheet" href="style/style.css">
	<script type="text/javascript" src="scripts/script.js"></script>
	
</head>
<body>
	
	<!-- Info Modal -->
	<div class="modal fade" id="psh-info-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body psh-modal-body">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<h1 class="modal-title center psh-modal-title">Info</h1>
									<p class='psh-modal-info-p'>This is a demonstration of a web interface of the attendance system, to see the statics of the working hours of the employees. It provides a whole view of the employee's daily working hours, monthly and yearly average working hours.</p>
									
								</div>
							</div>
							
							<hr class='psh-hr'> 

							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="psh-modal-info-name">
										<h4 class="panel-title center">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#psh-modal-info-name-collapse" aria-expanded="false" aria-controls="psh-modal-info-name-collapse">
												Name Search
											</a>
										</h4>
									</div>
									<div id="psh-modal-info-name-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="psh-modal-info-name">
										<div class="panel-body">
											<img class="psh-modal-info-img" src="imgs/name.gif" alt="">
											<p class="psh-modal-info-caption">To search specific employee, <b>click on the employee's name</b> or <b>search his/her name in the search bar</b>.</p>
										</div>
									</div>
								</div>

								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="psh-modal-info-year">
										<h4 class="panel-title center">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#psh-modal-info-year-collapse" aria-expanded="false" aria-controls="psh-modal-info-year-collapse">
												Year Search
											</a>
										</h4>
									</div>
									<div id="psh-modal-info-year-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="psh-modal-info-year">
										<div class="panel-body">
											<img class="psh-modal-info-img" src="imgs/year.gif" alt="">
											<p class="psh-modal-info-caption">To search specific year, <b>click on the year</b> or <b>choose the name in the year options</b>.</p>
										</div>
									</div>
								</div>

								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="psh-modal-info-daily">
										<h4 class="panel-title center">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#psh-modal-info-daily-collapse" aria-expanded="false" aria-controls="psh-modal-info-daily-collapse">
												Daily Chart
											</a>
										</h4>
									</div>
									<div id="psh-modal-info-daily-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="psh-modal-info-daily">
										<div class="panel-body">
											<img class="psh-modal-info-img" src="imgs/daily.gif" alt="">
											<p class="psh-modal-info-caption">To see daily data line chart, <b>click on the month</b> to see the daily or monthly chart.</p>
										</div>
									</div>
								</div>

								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="psh-modal-info-monthly">
										<h4 class="panel-title center">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#psh-modal-info-info-collapse" aria-expanded="false" aria-controls="psh-modal-info-info-collapse">
												Monthly Chart
											</a>
										</h4>
									</div>
									<div id="psh-modal-info-info-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="psh-modal-info-monthly">
										<div class="panel-body">
											<img class="psh-modal-info-img" src="imgs/monthly.gif" alt="">
											<p class="psh-modal-info-caption">To see monthly data line chart, <b>click on the year</b> to see the daily or monthly chart.</p>
										</div>
									</div>
								</div>

								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="psh-modal-info-sorting">
										<h4 class="panel-title center">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#psh-modal-info-sorting-collapse" aria-expanded="false" aria-controls="psh-modal-info-sorting-collapse">
												Sorting
											</a>
										</h4>
									</div>
									<div id="psh-modal-info-sorting-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="psh-modal-info-sorting">
										<div class="panel-body">
											<img class="psh-modal-info-img" src="imgs/sorting.gif" alt="">
											<p class="psh-modal-info-caption">To sort the all data, <b>click on the header</b> to sort them.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<p class="center">Designed by <a href="http://www.linuk.co.uk" target="_blank">Jheng-Hao Lin</a>.</p>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Main Content -->
	<div class="psh-main-container">
		<div class="container">
			<div class="row psh-content-container">
				<div class="col-md-6 col-md-offset-3 center">	
					<h1 class="psh-title animated fadeInLeft">Attendance System</h1>
					<span id='psh-info-icon' class='glyphicon glyphicon-info-sign animated fadeIn' data-toggle="modal" data-target="#psh-info-modal"></span>
				</div>

				<div class="col-md-10 col-md-offset-1">
					<div id="psh-chart-container"></div>
				</div>

				<div class="col-md-12 center">
					<hr class="psh-hr">
					<p class='psh-captions animated fadeInRight'>Average working duration ( hours )</p>
					<div class="clear"></div>
					<div id="psh-contents"></div>
				</div>
			</div>
		</div>
	</div>

	
</body>
</html>