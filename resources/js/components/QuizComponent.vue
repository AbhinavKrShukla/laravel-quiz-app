<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Online Examination
                        <span class="float-end">{{ questionIndex + 1 }}/{{ questions.length }}</span>
                    </div>

                    <div class="card-body">
                        <span class="float-end" style="color: red;">{{ formattedTime }}</span>
                        <div v-for="(question, index) in questions">
                            <div v-show="index === questionIndex">
                                {{ question.question }}
                                <ol>
                                    <li v-for="choice in question.answers">
                                        <label>
                                            <input type="radio"
                                                   :value="choice.is_correct === true ? true : choice.answer"
                                                   :name="index"
                                                   v-model="userResponses[index]"
                                                   @click="choices(question.id, choice.id)"
                                            >
                                            {{ choice.answer }}
                                        </label>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" v-show="questionIndex < questions.length">
                        <div v-show="questionIndex > 0">
                            <button class="btn btn-primary float-start" @click="prev()">Prev</button>
                        </div>
                        <div v-show="questionIndex !== questions.length - 1">
                            <button class="btn btn-primary float-end" @click="next(); postUserChoice()">
                                Next
                            </button>
                        </div>
                        <div v-show="questionIndex === questions.length - 1">
                            <button class="btn btn-danger float-end" @click="next(); postUserChoice()">Submit</button>
                        </div>
                    </div>

                    <div v-show="questionIndex === questions.length">
                        <p style="text-align: center">
                            You Got: {{ score() }}/{{ questions.length }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    props: ['quizid', 'quizQuestions', 'hasQuizPlayed', 'times'],
    data() {
        return {
            questions: this.quizQuestions,
            questionIndex: 0,
            userResponses: Array(this.quizQuestions.length).fill(false),
            currentQuestion: 0,
            currentAnswer: 0,
            clock: moment.duration(this.times, 'minutes'),
        };
    },
    mounted() {
        this.startTimer();
    },
    computed: {
        formattedTime() {
            return moment.utc(this.clock.asMilliseconds()).format("mm:ss");
        }
    },
    methods: {
        startTimer() {
            this.interval = setInterval(() => {
                if (this.clock.asSeconds() > 0) {
                    this.clock = moment.duration(this.clock.asSeconds() - 10, 'seconds');
                } else {
                    clearInterval(this.interval);
                    alert('Timeout!');
                    window.location.reload();
                }
            }, 1000);
        },
        next() {
            this.questionIndex++;
        },
        prev() {
            this.questionIndex--;
        },
        choices(question, answer) {
            this.currentQuestion = question;
            this.currentAnswer = answer;
        },
        score() {
            return this.userResponses.filter(val => val === true).length;
        },
        postUserChoice() {
            axios.post('user/quiz/create', {
                answerId: this.currentAnswer,
                questionId: this.currentQuestion,
                quizId: this.quizid,
            }).then(response => {
                console.log(response);
            }).catch(error => {
                alert(error);
            });
        }
    }
};
</script>
