<template>
<div>
        <b-container class="bv-example-row">
            <b-row>
                <b-col xl='4'>
                    แสดง : <b-form-select v-model="pickoption" :options="optionsitem"></b-form-select>
                </b-col>
            </b-row>
            <b-row>
                <b-col xl='2' v-if="pickoption">
                    ปี : <b-form-select v-model="pickyear" :options="years"></b-form-select>
                    
                </b-col>
                    
                <b-col xl='2' v-if="pickoption == 'b'">
                    เดือน : <b-form-select v-model="pickmonth" :options="months"></b-form-select>
                </b-col>
            </b-row>
            
            <b-row>
                <b-col xl='12'>
                    <v-btn width="100%" color="success" @click="show = true;getData();getList();">แสดง>>></v-btn>
                </b-col>
            </b-row>
                <div v-if="show == true" >
                    <line-chart :chart-id="'graph'"  :width="100" :height="35" :chart-data="datacollection"></line-chart>
                    <v-btn id='cl' absolute right large color="blue-grey lighten-5" @click="createPDF()"><v-icon left large>picture_as_pdf</v-icon>Save PDF</v-btn>
                </div>  
                <canvas  width="500" height="150" ></canvas>
        
        </b-container>
    </div>
    
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
import Chart from 'chart.js'
import LineChart from './Chart/Linechart'

import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;

pdfMake.fonts = {
  THSarabunNew: {
    normal: 'THSarabunNew.ttf',
    bold: 'THSarabunNew-Bold.ttf',
    italics: 'THSarabunNew-Italic.ttf',
    bolditalics: 'THSarabunNew-BoldItalic.ttf'
  },
  Roboto: {
    normal: 'Roboto-Regular.ttf',
    bold: 'Roboto-Medium.ttf',
    italics: 'Roboto-Italic.ttf',
    bolditalics: 'Roboto-MediumItalic.ttf'
  }
}

export default {
  components: {
    LineChart
  },
  data () {
    return {
        datacollection: null,
        show:false,
        bookings:[],
        pickyear:'2020',
        years:['2019','2020'],
        months:[
                { 
                    value:'1',
                    text:'มกราคม'
                },
                { 
                    value:'2',
                    text:'กุมภาพันธ์'
                },
                { 
                    value:'3',
                    text:'มีนาคม'
                },
                { 
                    value:'4',
                    text:'เมษายน'
                },
                { 
                    value:'5',
                    text:'พฤษภาคม'
                },
                { 
                    value:'6',
                    text:'มิถุนายน'
                },
                { 
                    value:'7',
                    text:'กรกฏาคม'
                },
                { 
                    value:'8',
                    text:'สิงหาคม'
                },
                { 
                    value:'9',
                    text:'กันยายน'
                },
                { 
                    value:'10',
                    text:'ตุลาคม'
                },
                { 
                    value:'11',
                    text:'พฤศจิกายน'
                },
                { 
                    value:'12',
                    text:'ธันวาคม'
                },
            ],
        pickmonth:'',
        optionsitem:[
            {
                value: 'a',
                text:'รายปี'
            },
            { 
                value: 'b',
                text:'รายเดือน'
            },
        ],
        pickoption:'a',
        monthly:[
            'มกราคม',
            'กุมภาพันธ์',
            'มีนาคม',
            'เมษายน',
            'พฤษภาคม',
            'มิถุนายน',
            'กรกฏาคม',
            'สิงหาคม',
            'กันยายน',
            'ตุลาคม',
            'พฤศจิกายน',
            'ธันวาคม'
            ],
        data:[],
        click:false,
        datalabel:[],
        rooms:[],
        joingroup:[],
        buildings:[],
        list:[],
        count:0,
        datalist:[],
        label:[],
        data:[],
        countnum:[],
        color : [
            '#D4F0F0',//ฟ้า
            '#F3B0C3',//ชมพู
            '#97C1A9',//เขียวเข้ม
            '#FFFFB5',//เหลือง
            '#CBAACB',//ม่วง
            '#FF96BA',//แดง
            '#6EB5FF',//น้ำเงิน
            '#BFFCC6'//เขียวเข้ม
        ]
    }
  },

  
  mounted :function() {
    this.getTableData();
    //this.getList();
    this.fillData();
    this.rooms.forEach((room,index));
    

  },
  
  methods: {
    fillData () {

      this.datacollection = {
        type: 'line',
        labels: this.datalabel,
        datasets: this.data,
        /*[
          {
            label: 'Data One',
            data: [this.getRandomInt(), this.getRandomInt()]
          },
        ]*/
      }
    },
    
    getRandomInt () {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5
    },
    
    createPDF(){
            var canvas = document.querySelector('#graph');
            var canvasImg = canvas.toDataURL("image/png");
            var month = ''
            switch (this.pickmonth) {
                case '1':
                    month = 'เดือน มกราคม'
                break;
                case '2':
                    month = 'เดือน กุมภาพันธ์'
                break;
                case '3':
                    month = 'เดือน มีนาคม'
                break;
                case '4':
                    month = 'เดือน เมษายน'
                break;
                case '5':
                    month = 'เดือน พฤษภาคม'
                break;
                case '6':
                    month = 'เดือน มิถุนายน'
                break;
                case '7':
                    month = 'เดือน กรกฏาคม'
                break;
                case '8':
                    month = 'เดือน สิงหาคม'
                break;
                case '9':
                    month = 'เดือน กันยายน'
                break;
                case '10':
                    month = 'เดือน ตุลาคม'
                break;
                case '11':
                    month = 'เดือน พฤศจิกายน'
                break;
                case '12':
                    month = 'เดือน ธันวาคม'
                break;
            
                default:
                    break;
            }
              var docDefinition = {
              
              content: [
                //{{picktimestart}} - {{picktimeend}}
                { text: 'รายงานการจองปี '+this.pickyear, fontSize: 15 ,},
                { text: month, fontSize: 15 ,},
                {
      // if you specify both width and height - image will be stretched
                  image: canvasImg,
                  width: 500,

                },
              ],
              defaultStyle:{
                font: 'THSarabunNew'
              }
            };
            pdfMake.createPdf(docDefinition).open()

        },
        
    getData(){
        while(this.datalabel.length != 0){

                this.datalabel.shift();
            }

        if(this.pickoption == 'a') {
            this.datalabel.push('มกราคม',
              'กุมภาพันธ์',
              'มีนาคม',
              'เมษายน',
              'พฤษภาคม',
              'มิถุนายน',
              'กรกฏาคม',
              'สิงหาคม',
              'กันยายน',
              'ตุลาคม',
              'พฤศจิกายน',
              'ธันวาคม')
            }
        else if (this.pickoption == 'b'){
             var month = this.pickmonth
                var year = this.pickyear
                var d = new Date(year, month, 0);
                var formatdate = Number(moment(d).format('DD'));

                for (var i = 1;i <= formatdate;i++){
                    this.datalabel.push(i);
                }
        }
        this.fillData();
        /*switch (this.pickoption) {
            case 'a':
                this.datalabel = this.linemonth;
                break;
            case 'b':
                
                var month = this.pickmonth
                var year = this.pickyear
                var d = new Date(year, month, 0);
                var formatdate = Number(moment(d).format('DD'));
                console.log(formatdate)
                for (var i = 1;i <= formatdate;i++){
                    this.datalabel.push(i);
                }

                break;
            default:
                break;
        }*/
        //var month = 0; // January
        //var d = new Date(2020, month + 1, 0);
        
        //console.log(formatdate)

        
    },
    getTableData:function (){
            axios.get('/api/bookings').then(response=>{
                this.bookings = response.data;
            });

            axios.get('/api/joingroups').then(response=>{
                this.joingroup = response.data;
            });

            axios.get('/api/rooms').then(response=>{
                this.rooms = response.data.room;

                this.rooms.forEach((room)=>{
                this.bookings.forEach((book)=>{
                    if(book.room_id == room.id)
                    this.list.push({
                        id:room.id,
                        bookingid:book.id,
                        roomid:room.id,
                        roomname:room.room_name,
                        create:book.start,
                    })
                })
            })
            });
            
            axios.get('/api/buildings').then(response=>{
                this.buildings = response.data.building;
            });
        },

        getList(){
            while(this.datalist.length != 0){

                this.datalist.shift();
            }
            while(this.data.length != 0){

                this.data.shift();
            }
            /*var formatmonth = moment(list.create).format('MM');
                    var formatyear = moment(list.create).format('YYYY');
                    var count = 0*/
            switch (this.pickoption) {
                case 'a':
                this.rooms.forEach((room)=>{
                    for (let i = 1; i < 13; i++) {
                        var count = 0;
                        for (let j = 0;j<this.list.length;j++){
                            if(room.id == this.list[j].roomid){
                            var formatmonth = Number(moment(this.list[j].create).format('MM'));
                                var formatyear = Number(moment(this.list[j].create).format('YYYY'));
                                if (formatyear == Number(this.pickyear))
                                if (formatmonth == i){
                                    count++;
                                }
                            }
                            
                        }

                        this.datalist.push({
                            roomid:room.id,
                            roomname:room.room_name,
                            count:count,
                            month:i,
                        })

                        count = 0;
                        
                    }
                })

                /*label: 'Data One',
                data: [this.getRandomInt(), this.getRandomInt()]*/

                if (this.datalist) {
                    this.rooms.forEach(this.CreateLineperMonth);
                }
                    break;
                case 'b':
                    var month = this.pickmonth
                    var year = this.pickyear
                    var d = new Date(year, month, 0);

                    var day = Number(moment(d).format('DD'));

                    this.rooms.forEach((room)=>{
                    for (let i = 1; i < day; i++) {
                        var count = 0;
                        for (let j = 0;j<this.list.length;j++){
                            if(room.id == this.list[j].roomid){
                            var formatmonth = Number(moment(this.list[j].create).format('MM'));
                            var formatyear = Number(moment(this.list[j].create).format('YYYY'));
                            var formatday = Number(moment(this.list[j].create).format('DD'));
                                if (formatyear == Number(this.pickyear))
                                if (formatmonth == Number(this.pickmonth)){
                                    if (formatday == i){
                                        count++;
                                    }
                                    
                                }
                            }
                            
                        }

                        this.datalist.push({
                            roomid:room.id,
                            roomname:room.room_name,
                            count:count,
                            month:Number(this.pickmonth),
                            day:i,
                        })

                        count = 0;
                        
                    }
                })

                if (this.datalist) {
                    //var countnum = [];

                    this.rooms.forEach(this.CreateLineperMonth);
                   
                }
                    break;
            
                default:
                    break;
            }
        },

        CreateLineperMonth:function (item,index) {
                        
                        var label = item['room_name'];
                        var color = this.color.valueOf();

                        for (let i = 0; i < this.datalist.length; i++) {
                            
                                
                            if(this.datalist[i].roomid == item['id']){

                                        this.countnum.push(this.datalist[i].count);
                                    }
                        }



                        this.data.push({
                                label:label,
                                backgroundColor: color[index],
                                pointBackgroundColor: 'white',
                                borderWidth: 1,
                                pointBackgroundColor: color[index],
                                pointBorderColor: color[index],
                                data:this.countnum,
                        });

                        this.countnum = []

                        //debugger;
                    
                },

        createData(data){
            this.rooms.forEach((room)=>{
                this.data.push({
                    label:room.room_name,
                    data:data
                })
            })
        }
    } 
}
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>