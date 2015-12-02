jQuery(function(){
	/*
	jQuery(".datepicker").datepicker({ 
	dateFormat: "mm/dd/yy",
	changeMonth: true, changeYear: true, yearRange: '1910:<?php echo date("Y"); ?>',
	onSelect: function(date) {
			/*
			thedate = new Date(date);
			thedate.setDate(thedate.getDate());
			jQuery(this).val(dateFormat(thedate, "mmm dd, yyyy"));
			id = jQuery(this).attr("alt");
			jQuery("#"+id).val(date);
			return false;
			
		},
	});
	*/

	//Brio

	/********************************
	popover
	********************************/
	if( $.isFunction($.fn.popover) ){
	$('.popover-btn').popover();
	}
	
	/********************************
	tooltip
	********************************/
	if( $.isFunction($.fn.tooltip) ){
	$('.tooltip-btn').tooltip()
	}
	
	/********************************
	NanoScroll - fancy scroll bar
	********************************/
	if( $.isFunction($.fn.niceScroll) ){
	$(".nicescroll").niceScroll({
	
		cursorcolor: '#9d9ea5',
		cursorborderradius : '0px'		
		
	});
	}
	
	if( $.isFunction($.fn.niceScroll) ){
	$("aside.left-panel:not(.collapsed)").niceScroll({
		cursorcolor: '#8e909a',
		cursorborder: '0px solid #fff',
		cursoropacitymax: '0.5',
		cursorborderradius : '0px'	
	});
	}

	/********************************
	Input Mask
	********************************/
	if( $.isFunction($.fn.inputmask) ){
		$(".inputmask").inputmask();
	}
	
	/********************************
	TagsInput
	********************************/
	if( $.isFunction($.fn.tagsinput) ){
		$('.tagsinput').tagsinput();
	}
	
	/********************************
	Chosen Select
	********************************/
	if( $.isFunction($.fn.chosen) ){
		$('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	}

	/********************************
	Scroll To Top
	********************************/
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});

	/********************************
	DateTime Picker
	********************************/
	if( $.isFunction($.fn.datetimepicker) ){
		$('.datetimepicker').datetimepicker({
			format: 'YYYY/MM/DD HH:mm:ss'
		});
		
		$('#datepicker').datetimepicker({pickTime: false});
		
		jQuery('#tx-filterdate').datetimepicker({
			format: 'YYYY/MM/DD'
		});

		//main layout
		$('input[name="start_date"]').datetimepicker({
			format: 'YYYY/MM/DD'
		});
        $('input[name="end_date"]').datetimepicker({
            useCurrent: false,
         	format: 'YYYY/MM/DD'
        });
        $('input[name="start_date"]').on("dp.change", function (e) {
            $('input[name="end_date"]').data("DateTimePicker").minDate(e.date);
        });
        $('input[name="end_date"]').on("dp.change", function (e) {
            $('input[name="start_date"]').data("DateTimePicker").maxDate(e.date);
        });


		//site_info
		$('#tx-filter-start-date').datetimepicker({
			format: 'YYYY/MM/DD'
		});
        $('#tx-filter-end-date').datetimepicker({
            useCurrent: false,
         	format: 'YYYY/MM/DD'
        });
        $("#tx-filter-start-date").on("dp.change", function (e) {
            $('#tx-filter-end-date').data("DateTimePicker").minDate(e.date);
        });
        $("#tx-filter-end-date").on("dp.change", function (e) {
            $('#tx-filter-start-date').data("DateTimePicker").maxDate(e.date);
        });

        //site_edit
		$('input[name="date_on"]').datetimepicker({
			format: 'YYYY/MM/DD'
		});
        $('input[name="date_off"]').datetimepicker({
            useCurrent: false,
         	format: 'YYYY/MM/DD'
        });
        $('input[name="date_on"]').on("dp.change", function (e) {
            $('input[name="date_off"]').data("DateTimePicker").minDate(e.date);
        });
        $('input[name="date_off"]').on("dp.change", function (e) {
            $('input[name="date_on"]').data("DateTimePicker").maxDate(e.date);
        });
		//$('.datepicker').datetimepicker({pickTime: false});
		//$('.timepicker').datetimepicker({pickDate: false});
		
		//$('.datetimerangepicker1').datetimepicker();
		//$('.datetimerangepicker2').datetimepicker();
		//$(".datetimerangepicker1").on("dp.change",function (e) {
		//   $('.datetimerangepicker2').data("DateTimePicker").setMinDate(e.date);
		//});
		//$(".datetimerangepicker2").on("dp.change",function (e) {
		//   $('.datetimerangepicker1').data("DateTimePicker").setMaxDate(e.date);
		//});
	}
	
	jQuery( "#dialog" ).dialog({ autoOpen: false, closeOnEscape: true, title: "",
		open: function(){
			setTimeout(function(){
				jQuery(".ui-dialog").fadeOut(200, function(){
					jQuery("#dialog").dialog("close");
				});
			}, 2000);
		}
	});

});

/********************************
Mask cell values
mask_values(selector,new_class,cell_value);
********************************/
function mask_values(s,nc,cv){
	$(s).each(function(){
		$(this).addClass(nc);
		var t = parseFloat($(this).html());
		if(t == 0){
			$(this).html(cv);
		}
	});
}

/******************************
 Generate chart for selected txo data
 
 generate_txodata_chart(object, thlabelname, chart, charttype);

*******************************/
function generate_txodata_chart(obj, l, c, ct){

	var id = obj.attr('id').replace('bt-','');
	var ch = obj.data('channel');
	var arr_categories = new Array();
	var arr_values = new Array();

	$(l+'-'+ch).each(function(){
		arr_categories.push ($(this).attr('id'));
	});

	$('#'+id+' .value').each(function(){
		arr_values.push($(this).data('value'));
	});

	$(c+'-'+ch).highcharts({
        title: {
            text: id,
            x: -20 //center
        },
        xAxis: {
            categories: arr_categories
        },
        yAxis: {
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [
        	{
	        	name: id,
	        	showInLegend: false,
	       		data: arr_values
   			}
   		],
   		chart: {
            type: ct,
            zoomType: 'xy'
    	}
    });
	
	//console.log('id:'+id+',ch:'+ch+',thlabel:'+l+',chart:'+c+',labels:'+arr_labels+',values:'+arr_values);
}

/************************************
 Generate chart all the charts for each channels
 generate_txodata_chart_summary(tableselector, thlabelname, chartname, channel, charttype){
*************************************/
function generate_txodata_chart_summary(t, l, c, ch, ct){
	
	var arr_row = new Array();
	var arr_categories = new Array();

	$(l+'-'+ch).each(function(){
		arr_categories.push ($(this).attr('id'));
	});

	$(t+' tr').each(function(){
		var name = $(this).attr('id');
		var data = new Array();

		var element = {};

		$('.value',this).each(function(){
			var val = parseFloat($(this).data('value'));
			if(isNaN(val)) val = 0

			data.push(val);
		});

		element.name = name,
		element.data = data;
		arr_row.push(element);

	});
	arr_row.splice(0,1);

	$(c+'-'+ch).highcharts({
        title: {
            text: 'Quick Looks',
            x: -20 //center
        },
        xAxis: {
            categories: arr_categories
        },
        yAxis: {
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: 
        	arr_row
        ,
   		chart: {
            type: ct,
            zoomType: 'xy'
    	}
    });
    //console.log(arr_categories);
	console.log(arr_row);
}

function alertX(data){
	//jAlert(data);
	jQuery("#dialoghtml").html(data);
	jQuery("#dialog").dialog("open"); 
	
	
}

function confirmX(data){
	return jConfirm(data);
}

/****************** JS Date ***************************/
var dateFormat = function () {
var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
	timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
	timezoneClip = /[^-+\dA-Z]/g,
	pad = function (val, len) {
		val = String(val);
		len = len || 2;
		while (val.length < len) val = "0" + val;
		return val;
	};

// Regexes and supporting functions are cached through closure
return function (date, mask, utc) {
	var dF = dateFormat;

	// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
	if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
		mask = date;
		date = undefined;
	}

	// Passing date through Date applies Date.parse, if necessary
	date = date ? new Date(date) : new Date;
	if (isNaN(date)) throw SyntaxError("invalid date");

	mask = String(dF.masks[mask] || mask || dF.masks["default"]);

	// Allow setting the utc argument via the mask
	if (mask.slice(0, 4) == "UTC:") {
		mask = mask.slice(4);
		utc = true;
	}

	var	_ = utc ? "getUTC" : "get",
		d = date[_ + "Date"](),
		D = date[_ + "Day"](),
		m = date[_ + "Month"](),
		y = date[_ + "FullYear"](),
		H = date[_ + "Hours"](),
		M = date[_ + "Minutes"](),
		s = date[_ + "Seconds"](),
		L = date[_ + "Milliseconds"](),
		o = utc ? 0 : date.getTimezoneOffset(),
		flags = {
			d:    d,
			dd:   pad(d),
			ddd:  dF.i18n.dayNames[D],
			dddd: dF.i18n.dayNames[D + 7],
			m:    m + 1,
			mm:   pad(m + 1),
			mmm:  dF.i18n.monthNames[m],
			mmmm: dF.i18n.monthNames[m + 12],
			yy:   String(y).slice(2),
			yyyy: y,
			h:    H % 12 || 12,
			hh:   pad(H % 12 || 12),
			H:    H,
			HH:   pad(H),
			M:    M,
			MM:   pad(M),
			s:    s,
			ss:   pad(s),
			l:    pad(L, 3),
			L:    pad(L > 99 ? Math.round(L / 10) : L),
			t:    H < 12 ? "a"  : "p",
			tt:   H < 12 ? "am" : "pm",
			T:    H < 12 ? "A"  : "P",
			TT:   H < 12 ? "AM" : "PM",
			Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
			o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
			S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
		};

	return mask.replace(token, function ($0) {
		return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
	});
};
}();


/********************************** number formating *****************************/

function addCommas(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function fNum(num){
	num = uNum(num);
	num = num.toFixed(2);
	return addCommas(num);
}
function uNum(num){
	if(!num){
		num = 0;
	}
	else if(isNaN(num)){
		num = num.replace(/[^0-9\.]/g, "");
		if(isNaN(num)){
			num = 0;
		}
	}
	return num*1;
}

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};

function addDays(date, daystoadd){
	if(daystoadd==""){
		daystoadd = 0;
	}
	daystoadd = Math.ceil(daystoadd);

	if(date){
		date = date.split(",");
		date = date[0].split("/");
		date = date[1]+"/"+date[0]+"/"+date[2];

		try{
			thedate = new Date(date);
			thedate.setDate(thedate.getDate()+daystoadd);
			return dateFormat(thedate, "dd/mm/yyyy, dddd");
		}
		catch(e){
		}
		
	}
}