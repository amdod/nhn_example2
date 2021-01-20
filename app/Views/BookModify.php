<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta charset="utf-8"/>
    <title>
        가계부
    </title>
</head>
<body>


<div id="app">
<template>
    <v-app-bar> 
      가계부 수정하기
    </v-app-bar> 
    <v-app>
    <v-form>
        <v-container style="maxWidth: 700px;">
        <v-row>
        <v-col
        class="d-flex"
        cols="12"
        sm="6"
        >
        <v-select
            v-model="use_type"
            :items="use_types"
            label="수입/지출 선택"
        >
        </v-select>
        </v-col>
        <v-col>
            <v-text-field
            :counter="10" 
            label="수정할 가격" 
            name="cost" 
            v-model="cost" 
            maxlength="10" >
            </v-text-field>
        </v-col>
        </v-row>
            <v-row>
            <v-text-field
            :counter="10" 
            label="수정할 메모" 
            name="memo" 
            v-model="memo" 
            maxlength="10" >
            </v-text-field>
            </v-row>
            
    <v-row>
    <v-col
      cols="12"
      sm="6"
      md="4"
    >
      <v-menu
        ref="selectDate"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="date"
            label="날짜"
            prepend-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="date"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menu = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.selectDate.save(date)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
    </v-col>

    <v-spacer></v-spacer>

    <v-col
      cols="11"
      sm="5"
    >
      <v-menu
        ref="selectTime"
        v-model="menu3"
        :close-on-content-click="false"
        :nudge-right="40"
        :return-value.sync="time"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="time"
            label="시간"
            prepend-icon="mdi-clock-time-four-outline"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-time-picker
          v-if="menu3"
          v-model="time"
          full-width
          @click:minute="$refs.selectTime.save(time)"
        ></v-time-picker>
      </v-menu>
    </v-col>

    </v-row>

                <v-row>
                <v-btn
                    block outlined color="blue"
                    @click="modifyClick">
                        수정
                </v-btn>
                </v-row>
        </v-container>
    </v-form>
</v-app>
</template>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


<script> 

new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data: {
        date: new Date().toISOString().substr(0, 10),
        menu: false,
        modal: false,
        menu2: false,

        time: null,
        menu3: false,
        modal2: false,

        use_types: ['지출', '수입'],
        
        id: '',
        memo: '',
        use_type: '',
        cost: ''
      },
    
    created() { 
        console.log('fetch list') 
        this.fetch()
    }, 

    methods: { 
        fetch() { 
            const data = location.pathname.split('/');
            const id = data[data.length - 1];
            console.log('fetch list') 
            axios.get(`http://localhost/public/bookcontroller/book/${id}`)
            .then((response) => {
                console.log(response)
                this.id = response.data.id;
                console.log(response.data.id);
                this.memo = response.data.memo;
                var temp = response.data.use_date.split(" ");
                this.date = new Date(temp[0]).toISOString().substr(0, 10);
                var temp2 = temp[1].split(":");
                this.time = temp2[0] + ":" + temp2[1];
                this.cost = response.data.cost;
            })
            .catch((error) => {
                console.log(error) 
            })
            },
        
        modifyClick() { 
            const form = new FormData();
            form.append("id", this.id);
            form.append("memo", this.memo);
            form.append("use_date", this.date + " " + this.time);
            form.append("use_type", this.use_type);
            form.append("cost", this.cost);
            

            console.log('fetch list') 
            axios.post(`http://localhost/public/bookcontroller/update/${this.id}`, form)
            .then((response) => {
                console.log(response)
                window.location.href = `http://localhost/public/home`
            })
            .catch((error) => {
                console.log(error) 
            })
            },
            

    }
})

</script> 

</body>
</html>
