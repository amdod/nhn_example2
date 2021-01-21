<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta charset="UTF-8">
    <title>
        가계부
    </title>
</head>
<body>


<div id="app">
<template>
    <v-app>
  <v-row class="fill-height">
    <v-col>
      <v-sheet height="64">
        <v-toolbar
          flat
        >
          <v-btn
            outlined
            class="mr-4"
            color="grey darken-2"
            @click="setToday"
          >
            오늘
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="prev"
          >
            <v-icon small>
              mdi-chevron-left
            </v-icon>
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="next"
          >
            <v-icon small>
              mdi-chevron-right
            </v-icon>
          </v-btn>
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-title>
          <span class="blue--text">수입: {{ total_income() }}</span>
            <v-spacer></v-spacer>
            <span class="red--text">지출: {{ total_use() }}</span>
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu
            bottom
            right
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                outlined
                color="grey darken-2"
                v-bind="attrs"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>
                  mdi-menu-down
                </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>일</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>주</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>월</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 일</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="600">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="events"
          :event-color="getEventColor"
          :type="type"
          @click:event="showEvent"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"
        ></v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            min-width="350px"
            flat
          >
            <v-toolbar
              :color="selectedEvent.color"
              dark
            >
              <v-btn icon>
                <v-icon
                @click="modifyClick(selectedEvent.id)"
                >mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <span v-html="selectedEvent.status">
              </span>
              <span v-html="selectedEvent.cost">
              </span>
              <span>원</span>
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click="selectedOpen = false"
              >
                취소
              </v-btn>
              <!-- <v-btn
                text
                color="secondary"
                @click="deleteClick(selectedEvent.id)"
              >
                삭제
              </v-btn> -->
              <v-dialog
                v-model="dialog"
                persistent
                max-width="290"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                  color="secondary"
                  v-bind="attrs"
                  v-on="on"
                  >
                    삭제
                  </v-btn>
                </template>
              <v-card>
                <v-card-title class="subtitle-1">
                  <span class="red--text">정말 삭제하시겠습니까?</span>
                </v-card-title>
                <v-card-actions>
                <v-spacer></v-spacer>
                  <v-btn
                    color="green darken-1"
                    text
                    @click="dialog = false"
                  >
                    아니오
                </v-btn>
                <v-btn
                  color="green darken-1"
                  text
                  @click="deleteClick(selectedEvent.id)"
                  >
                    예
                </v-btn>
                </v-card-actions>
              </v-card>
              </v-dialog>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>

      <div align="center">
      <v-container>
                <v-btn 
                color="primary"
                @click="writeClick">
                    추가하기
                </v-btn>
      </v-container>
      </div>
      
    </v-col>
  </v-row>
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
    
    data: () => ({
      focus: '',
      type: 'month',
      typeToLabel: {
        month: '월',
        week: '주',
        day: '일',
        '4day': '4 일',
      },
      dialog: false,
      selectedMonthEvents: [],
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      events: [],
      colors: ['blue','red'],
      names: [],
    }),
    mounted () {
      this.$refs.calendar.checkChange()
    },
    
    methods: {
      viewDay ({ date }) {
        this.focus = date
        this.type = 'day'
      },
      getEventColor (event) {
        return event.color
      },
      setToday () {
        this.focus = ''
      },
      prev () {
        this.$refs.calendar.prev()
      },
      next () {
        this.$refs.calendar.next()
      },
      showEvent ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          setTimeout(() => {
            this.selectedOpen = true
          }, 10)
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          setTimeout(open, 10)
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      updateRange ({ start, end }) {
        console.log('fetch list')
            axios.get(`http://localhost/public/bookcontroller/dateselect/${start.date}/${end.date}`)
            .then((response) => {
                console.log(response)
                console.log(response.data.events)
                const temp = []
                var num = -1

                response.data.events.forEach(item => {
                  if (item.use_type == "수입") {
                    num = 0;
                  }
                  else {
                    num = 1;
                  }

                  temp.push({
                  id: item.id,
                  name: item.memo,
                  status: item.use_type,
                  start: item.use_date,
                  end: item.use_date,
                  cost: item.cost,
                  color: this.colors[num],
                  timed: false
                });
                });

                this.events = temp;
            })
            .catch((error) => {
                console.log(error) 
            })
      },

      deleteClick(id) {
            axios.delete(`http://localhost/public/bookcontroller/delete/${id}`)
            .then((response) => {
                console.log(response)
                const temp = []

              console.log(id)
                this.events.forEach(item => {
                  if(item.id != id){
                    temp.push(item);
                  }
                });
                
                this.events = temp;
                this.selectedOpen = false
            })
            .catch((error) => {
                console.log(error) 
            })
        },
        writeClick() { 
            window.location.href = `http://localhost/public/home/bookcreate` 
        }, 
        modifyClick(id) { 
            window.location.href = `http://localhost/public/home/bookmodify/${id}` 
        }, 

        total_income() {
            var total = 0;
              this.events.forEach(item => {
              if(item.status == "수입"){
                total += Number(item.cost);
              }
            });

          return total;
          },

          total_use() {
            var total = 0;
              this.events.forEach(item => {
              if(item.status == "지출"){
                total += Number(item.cost);
              }
            });

          return total;
          },

      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },
    },
  }
    
)

</script> 

</body>
</html>