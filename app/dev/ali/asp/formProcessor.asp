<%@ language="javascript"%>
<html>
<head>
	<title>Congragulations!</title>
	<%
	var answer = Request.Form("answer");
	var sunday = Request.Form("Sunday");
	var monday = Request.Form("Monday");
	var tuesday = Request.Form("Tuesday");
	var wensday = Request.Form("Wensday");
	var thursday = Request.Form("Thursday");
	var friday = Request.Form("Friday");
	var saturday = Request.Form("Saturday");
	
	function buildTable()
	{
		var day;
		for(day = 0; day<7; day++)
		{
			switch(day)
			{
				case 0:
					if(sunday!=0)
					{
						temp+="<tr><td> Sunday </td>";
						timeOfDay(sunday);
					}
					break;
				case 1:
					if(monday!=0)
					{
						temp+="<tr><td> Monday </td>";
						timeOfDay(monday);
					}
					break;
				case 2:
					if(tuesday!=0)
					{
						temp+="<tr><td> Tuesday </td>";
						timeOfDay(tuesday);
					}
					break;
				case 3:
					if(wensday!=0)
					{
						temp+="<tr><td> Wensday </td>";
						timeOfDay(wensday);
					}
					break;
				case 4:
					if(thursday!=0)
					{
						temp+="<tr><td> Thursday </td>";
						timeOfDay(thursday);
					}
					break;
				case 5:
					if(friday!=0)
					{
						temp+="<tr><td> Friday </td>";
						timeOfDay(friday);
					}
					break;
				case 6:
					if(saturday!=0)
					{
						temp+="<tr><td> Saturday </td>";
						timeOfDay(saturday);
					}
					break;
			}
		}
	}
	
	function timeOfDay(x)
	{
		if(x==1)
			temp += "<td> 8:00-9:00 </td></tr>";
		if(x==2)
			temp += "<td> 12:00-1:00 </td></tr>";
		if(x==3)
			temp += "<td> 5:00-6:00 </td></tr>";
	}
	
	function alertBox()
	{
		alert('The "website" link leads to an empty document. Making this document could be a future project.');
	}
	%>
</head>
<body bgcolor="pink" text="CornFlowerBlue">
	<%
	Response.write("<h1 align='center'> Congragulations, "+ Request.Form('firstName')+ ", you are now a member of Ali's Kniting Club! </h1>");
	
	if(sunday!=0 || monday!=0 || tuesday!=0 || wensday!=0 || thursday!=0 || friday!=0 || saturday!=0)
	{
		Response.write("<p align='center'> You are scheduled for the following meetings every week: </p>");
		var temp = "<table align='center' border='1'><tr><th> Day </th><th> Time </th></tr>";
		buildTable();
		temp += "</table>";
		Response.write(temp);
		if(answer=1)
			Response.write("<p align='center'> In the meetings in your first week you will be given a kniting tutorial, so you can learn how to knit. </p>");
		Response.write("<p align='center'>If you ever want to change your availability settings, just go to my <a href='http://server/ProjectAli/asp/website.html'>website.<a></p>");
	}
	else
	Response.write("<p align='center'>You have no meetings scheduled, however you can change that at my <a href='http://server/ProjectAli/asp/website.html'>website.<a></p>");
	%>
	
</body>
</html>