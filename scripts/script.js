
$(function() {
	
	/*=========================================
	=            Global Variables            =
	=========================================*/
	
	// set a year list to store the years from statics, set it global to prevent it changing 
	// when the query change.
	let yearList = []

	// set a name list to store the names for autoCompleteSearch
	let nameList = []
	
	// let pshModalChart = null
	
	/*=====  End of Global Variables  ======*/

	/*======================================
	=            Main Functions            =
	======================================*/
	
	main()
	function main () {
		displayTable()
	}
	
	/*=====  End of Main Functions  ======*/
	

	/*==========================================
	=           Search Bar function            =
	==========================================*/

	function addSearchBarListerner(){
		$("#psh-search").on('keyup input', ()=>{
			if($("#psh-search").val().length===0){
				displayTable()
			}else{
			}
		})	
	}
	
	// fetch name list for autocomplete function 
	function autoCompleteSearch(nameList){
		$( "#psh-search" ).autocomplete({
			source: nameList,
			close: ()=>{
				displayTable()
			}
		})
	}

	/*=====  End of Search Bar Function  ======*/
	
	/*==============================================
	=            Fetch and Show Statics            =
	==============================================*/

	function displayTable(){
		
		let year = $('#psh-table-yearButton').val()
		// set year to null for nonspecific year 
		if( year == "All Year" ){ year = null }
		
		let name = $("#psh-search").val()		
		
		appendTableRows(year, name)
		
	}


	function appendTableRows(year, name){
		$.post({
			url: 'api/fetch_data.php',
			data: {
				year: year,
				name: name
			},
			success: function(result){
				// console.log(JSON.parse(result))
				let contents = getTableRows(JSON.parse(result))
				updateTable(contents)
				autoCompleteSearch(nameList)
				addSearchBarListerner()

			}
		})
	}


	// update table contents
	function updateTable (contents) {
		
		// add fade out animation to the old data
		// BUT this will also add the class to the new contents
		// so we will remove it after the fade out animation finishing.
		$('#psh-table .psh-table-row').addClass('fadeOutDown')

		
		// remove the old data with animation
		setTimeout(()=>{
			
			// remove the old data rows after animation
			$('#psh-table .psh-table-row').remove()
	
			// remove the fadeOutDonw animation from the new contents, when using sorting, the 
			// contents will be an array
			if($.type(contents)=='array'){
				contents.forEach((row)=>{
					$(row).removeClass('fadeOutDown')
				})	
			}

			$('#psh-table').children('tbody').append(contents)

			removeSortingIcon()
			addSortingIcon()
			addTableRowDataListener()			
			addTableRowNameListener()
			addTableRowYearListenr()
			addTableRowYearlyListenr()

		}, 500)

		function addTableRowYearListenr(){
			$('.psh-table-data-year').on('click', (event)=>{
				let year = $(event.target).html()
				if( year !== $('#psh-table-yearButton').val()){
					$('#psh-table-yearButton').val(year)
					displayTable()
				}
			})
		}

		function addTableRowYearlyListenr(){
			$('.psh-table-data-yearly').on('click', (event)=>{

				let name = $(event.target).parent().children('th.psh-table-data-name').html()
				let year = $(event.target).parent().children('th.psh-table-data-year').html()
				let datas = $(event.target).parent().children('th.psh-table-data')
				let month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ]
				let hours = []

				for(let i = 0; i<datas.length; i++){
					hours.push($(datas[i]).html())
				}

				
				resetCanvas()
				generateChart(name, year, "", month, hours)

				$('.selected').removeClass('selected')
				$(event.target).addClass('selected')

			})
		}

		function addTableRowNameListener () {
			$('.psh-table-data-name').on('click',(event)=>{
				
				let name = $(event.target).html()		
				
				// if the name is not the one now selected
				if(name !== $('#psh-search').val()){
					
					let year = $('#psh-table-yearButton').val()
					// set year to null for nonspecific year 
					if( year == "All Year" ){ year = null }

					// update new the data row
					appendTableRows(year, name)

					// change the value of the search bar to selected name
					$('#psh-search').val(name)

				}

			})
		}

		// add click listener on each row data 
		function addTableRowDataListener(){
			
			$("th.psh-table-data").on("click", (event)=>{
				let name = $(event.target).data('name'),
					year = $(event.target).data('year'),
					month = $(event.target).data('month'),
					day = $(event.target).data('day'),
					hours = $(event.target).data('hours')
			
				addChartContents(name, year, month, day, hours)
				$('.selected').removeClass('selected')
				$(event.target).addClass('selected')
			})

		}

		function addSortingIcon(){
			
			// different icon for different sorting order 
			let order = $('.psh-table-header-sort.selected').data('order')			
			if(order == -1){
				$('.psh-table-header-sort.selected').append('<span class="glyphicon glyphicon-triangle-bottom "></span>')	
			}else if(order == 1){
				$('.psh-table-header-sort.selected').append('<span class="glyphicon glyphicon-triangle-top "></span>')	
			}

		}

		function removeSortingIcon () {
			$('.glyphicon-triangle-bottom.glyphicon').remove()
			$('.glyphicon-triangle-top.glyphicon').remove()
		}

	}


	function getTableRows(result){
			
		// set a string and a array for storing table contents and year list
		let tableContents = "", years = []

		for (let user in result){
			
			// append the names into the name list
			if(nameList.indexOf(result[user]['name'])<0) {
				nameList.push(result[user]['name'])
			}

			// add new name to a new row
			for (let year in result[user]['statics']){

				// add new new in the years array for the year button
				if(years.indexOf(year)<0) years.push(year)

				// start a new row 
				let newRow = "<tr class='psh-table-row animated fadeInUp'><th class='psh-table-data-name'>"+result[user]['name']+"</th>"

				// append the year
				newRow += "<th class='psh-table-data-year'>" + year + "</th>"

				// calculate average working hours of a month
				let sumOfYear = 0, numOfDays = 0
				for (let month in result[user]['statics'][year]){
					
					let eachMonth = result[user]['statics'][year][month]
					let sumOfMonth = 0
					let dataDay = []
					let dataHours = []
					let dataYear = year
					let dataMonth = month
					let dataName = result[user]['name']

					// sum the total working hours of a month
					for (let day in eachMonth){
						sumOfMonth += parseFloat(eachMonth[day])
						dataHours.push(eachMonth[day])
						dataDay.push(day)
						numOfDays += 1
					}
					// add the sum of every month working hours
					sumOfYear += sumOfMonth

					// round the avg to at most 2 decimal places
					let avg = Math.round(sumOfMonth/dataDay.length*100)/100

					// console.log(dataName)
					newRow += '<th class="psh-table-data" data-year=' + dataYear + ' data-month=' + dataMonth + ' data-day=' + dataDay + ' data-hours="' + dataHours + '" data-name="'+ dataName +'" data-toggle="modal" data-target="#psh-modal">' + avg.toString() + "</th>"
				}
				
				// add avg of the year
				newRow += "<th class='psh-table-data-yearly'>"+Math.round(sumOfYear/numOfDays*100)/100+"</th>"

				// add sum of the year
				newRow += "<th class='psh-table-data-total'>"+Math.round(sumOfYear*100)/100+"</th>"

				// close tr tag 
				newRow += "</tr>"
				tableContents += newRow
			}

			// set the global yearList to years, only set it for the first time
			if(yearList.length==0) { 
				yearList = years 
				createTableHeader(yearList)

			}
		}

		return tableContents
	}


	function createTableHeader(yearList){

		let yearButton = createYearButton(yearList)
		let nameSearch = createNameSearch()
		// create table header
		$("#psh-contents").html('<table class="table table-hover" id="psh-table"><thead><tr class="animated fadeInDown psh-table-header"><th>' +nameSearch + '</th><th>'+yearButton+'</th><th class="psh-table-header-sort" data-order=1>Jan</th><th class="psh-table-header-sort" data-order=1>Feb</th><th class="psh-table-header-sort" data-order=1>Mar</th><th class="psh-table-header-sort" data-order=1>Apr</th><th class="psh-table-header-sort" data-order=1>May</th><th class="psh-table-header-sort" data-order=1>Jun</th><th class="psh-table-header-sort"data-order=1>Jul</th><th class="psh-table-header-sort" data-order=1>Aug</th><th class="psh-table-header-sort" data-order=1>Sep</th><th class="psh-table-header-sort" data-order=1>Oct</th><th class="psh-table-header-sort" data-order=1>Nov</th><th class="psh-table-header-sort" data-order=1>Dec</th><th class="psh-table-header-sort" data-order=1>Yearly</th><th class="psh-table-header-sort psh-table-data-total" data-order=1>Total</th></tr></thead><tbody></tbody></table>')

		// add onchange listern on the year button
		$('#psh-table-yearButton').on('change',()=>{
			displayTable()
		})

		// add header listener on the header for sorting
		$('.psh-table-header th.psh-table-header-sort').on('click', (event)=>{

			let nth = $(event.target).prevAll().length || 0
			let order = $(event.target).data('order') || -1

			sortTable(nth, order)

			// remove the selected class from the previous selected 
			$('.selected').removeClass('selected')

			// add selected class to change the background colour
			$(event.target).addClass('selected')

			// reverse the order for reversing data sorting 
			$(event.target).data('order',order*-1)

		})


		function createNameSearch () {
			return '<div class="psh-search-container"><input type="text" id="psh-search" class="psh-search-bar center center-block" placeholder="Name"></div>'
		}


		function createYearButton(yearList){
			
			// create year button
			var yearButton = '<select id="psh-table-yearButton"><option>All Year</option>'
			yearList.reverse().forEach((year)=>{
				yearButton += "<option>"
				yearButton += year
				yearButton += "</option>"
			})
			yearButton += '</select>'

			return yearButton
		}


	}


	

	/*=====  End of Fetch and Show Statics  ======*/


	/*=====================================
	=            Table Sorting            =
	=====================================*/
	

	function sortTable(nth, order) {

		let rows = $('#psh-table tbody tr').get()
		let sortedRows = sortRows(rows, nth, order)
		updateTable(sortedRows)

		function sortRows (rows, nth, order) {
	
			rows.sort((rowA,rowB)=>{
				let valueA = getRowValue(rowA)
				let valueB = getRowValue(rowB)
				if( valueA > valueB){
					return -1*order
				}else if( valueA < valueB){
					return 1*order
				}else{
					return 0
				}
			})

			return rows
		}
		
	
		function getRowValue(row){
			
			let value = $(row).children('th').eq(nth).text().toUpperCase()			
			if($.isNumeric(value)){ 
				value = parseFloat(value, 10) 
			}
			return value
		}
	}

	

	
	/*=====  End of Table Sorting  ======*/
	


	/*==============================================
	=            Month Data Query, Chart            =
	===============================================*/
	


	function addChartContents (name, year, month, day, hours) {
		newMonth = getMonthAbbr(month)

		let dayList = day.split(',')
		let newDayList =[]
		dayList.forEach((day)=>{
			newDayList.push(month+"/"+day)
		})	

		let hourList = hours.split(',')
		resetCanvas()
		generateChart(name, year, month, newDayList, hourList)
	}

	function getMonthAbbr(month) {
		var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]
		return monthNames[month-1]
	}

	function removeCanvas () {
		$('#psh-chart-container .psh-chart-close').remove()
		$('#psh-chart').remove()
		$('.chartjs-hidden-iframe').remove()
	}

	function setCanvas () {
		$('#psh-chart-container').append('<i class="glyphicon glyphicon-remove psh-chart-close"></i><canvas id="psh-chart"></canvas>')
		$(".psh-chart-close").on('click', ()=>{
			removeCanvas()
			$('.psh-table-data.selected').removeClass('selected')
		})
	}

	function resetCanvas(){
		removeCanvas()
		setCanvas()
	}

	function generateChart (name, year, month, dayList, hourList) {

		let modalChart = $("#psh-chart")
		let pshModalChart = new Chart(modalChart, {
			type: 'line',
			data: {
				labels: dayList,
				datasets: [{
					label: "working hours",
					data: hourList
				}]
			},

			options: {
				title: {
					display: true,
					text: name + " - " + month + "/" + year,
					fontFamily: 'Source Sans Pro',
					fontSize: 24,
					fontStyle: 'normal',
					position: 'bottom',
					fontColor: '#555'
				},
				legend: {
					display: false
				},
				tooltips: {
					displayColors: false,
					backgroundColor: 'rgba(86,86,86,0.8)'
				},
				scales: {
					yAxes: [{
						scaleLabel: {
							display: true,
							labelString: 'Working duration (hours)'
						}
					}]
				} 
			}
		})
	}

	/*=====  End of Month Data Query, Chart   ======*/
})

