var questions = [
		{
			"title": "This is a Test =)",
			"count": 3,
			"description": "Я хз, что тут вписать, поэтому тут какой-то текст"
		},
		{  
			"id":1,
			"question":"This is 1 test question for my test generator. Answer 1.",
			"answers":["1", "2", "3","4"],
			"right":"1",
			"description": "Я хз, что тут вписать, поэтому тут какой-то текст"
		},
		{  
			"id":2,
			"question":"This is 2 test question for my test generator. Answer two.",
			"answers":["one", "two", "three","four"],
			"right":"two",
			"description": "Я хз, что тут вписать, поэтому тут какой-то текст"
		},
		{  
			"id":3,
			"question":"This is 3 test question for my test generator. Answer III.",
			"answers":["I", "II", "III", "IV"],
			"right":"III",
			"description": "Я хз, что тут вписать, поэтому тут какой-то текст"
		}
	],
	index = 1, progress = 0, userAnswers = [];

$(document).ready(function () {
	$(".page-header > h1").text(questions[0].title)
							.append(' <small><span class="tests_done">0</span>/<span class="count">' +
							questions[0].count + '</span> <span class="timer">Timer</span></small>');
	setNewQuestion();

	$("button").click(function (e) {
		if (!$("button").hasClass('disabled')) {
			e.preventDefault();
			userAnswers.push($("a.active").text());
			progress++;
			setProgress();
			updateProgressBar();
			if (index < questions[0].count) {
				index++;
				setNewQuestion();
				$("button").addClass("disabled");
			}
			else {
				$("section").html("")
					.append('<div class="col-md-12"><table class="table table-hover"></table></div>');
				$("table.table").html("<thead><tr><th></th><th>Your answer</th><th>Right answer</th>" +
										"<th>Description</th></tr></thead><tbody></tbody>");
				checkAnswers();
			}
		}
	});
});

function handleClick(e) {
	removeActive();
	$(e).addClass('active');
	$("button").removeClass("disabled");	
}

function setProgress() {
	$("small > .tests_done").text(progress);
}

function removeActive() {
	$("a.list-group-item").each(function () {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			return ;
		}
	});
}

function setNewQuestion() {
	$("p.question").text(questions[index].question);
	$('.list-group').html("");
	for (var i = 0; i < questions[index].answers.length; i++) {
		$('.list-group').append('<a href="#" onclick="handleClick(this)" class="list-group-item">' +
								questions[index].answers[i] + '</a>');
	}
}

function checkAnswers() {
	var className = '';
	for (var i = 0; i < progress; i++) {
		className = (userAnswers[i] == questions[i + 1].right) ? 'success' : 'danger'; 
		$("tbody").append('<tr class="' + className + '"><td>' + questions[i + 1].id +
						'</td><td>' + userAnswers[i] + '</td><td>' + questions[i + 1].right +
						'</td><td>' + questions[i + 1].description + '</td></tr>');
	}
}

function updateProgressBar() {
	$(".progress-bar").css({'width': progress / questions[0].count * 100 + '%'});
}