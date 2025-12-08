const PersianDatePicker = {
    template: `
    <div class="persian-picker-wrapper">
        <input type="text" 
               class="form-control"
               :placeholder="placeholder"
               :readonly="true"
               :value="modelValue"
               @click="open=!open">
        <div v-if="open" class="persian-picker-panel shadow">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <button class="btn btn-sm btn-light" @click="prevMonth">&lt;</button>
                <strong>{{ jYear }}/{{ jMonth }}</strong>
                <button class="btn btn-sm btn-light" @click="nextMonth">&gt;</button>
            </div>
            <div class="grid text-center fw-bold">
                <div v-for="d in ['ش','ی','د','س','چ','پ','ج']">{{ d }}</div>
            </div>
            <div class="grid text-center mt-1">
                <div v-for="n in firstDay" class="empty"></div>
                <div v-for="day in daysInMonth" class="day" @click="select(day)">{{ day }}</div>
            </div>
        </div>
    </div>
    `,
    props:["modelValue","placeholder"],
    emits:["update:modelValue"],
    data(){ 
        const now=new Date();
        return {open:false,jYear:this.toJalali(now).jy,jMonth:this.toJalali(now).jm};
    },
    computed:{
        daysInMonth(){ return this.jMonth<=6?31:(this.jMonth<=11?30:29); },
        firstDay(){
            const {gy,gm,gd}=this.toGregorian(this.jYear,this.jMonth,1);
            return new Date(gy,gm-1,gd).getDay();
        }
    },
    methods:{
        select(day){
            const formatted=`${this.jYear}/${String(this.jMonth).padStart(2,'0')}/${String(day).padStart(2,'0')}`;
            this.$emit("update:modelValue",formatted);
            this.open=false;
        },
        nextMonth(){ if(this.jMonth===12){this.jMonth=1;this.jYear++;}else this.jMonth++; },
        prevMonth(){ if(this.jMonth===1){this.jMonth=12;this.jYear--;}else this.jMonth--; },
        toJalali(g){
            let gy=g instanceof Date?g.getFullYear():g.gy,gm=g instanceof Date?g.getMonth()+1:g.gm,gd=g instanceof Date?g.getDate():g.gd;
            const g_d_m=[0,31,(gy%4===0?29:28),31,30,31,30,31,31,30,31,30,31];
            let gy2=gm>2?gy+1:gy;
            let days=355666+365*gy+Math.floor((gy2+3)/4)-Math.floor((gy2+99)/100)+Math.floor((gy2+399)/400);
            for(let i=1;i<gm;++i)days+=g_d_m[i];
            days+=gd;
            let jy=-1595+33*Math.floor(days/12053);
            days%=12053;
            jy+=4*Math.floor(days/1461);
            days%=1461;
            if(days>365){jy+=Math.floor((days-1)/365);days=(days-1)%365;}
            let jm=days<186?1+Math.floor(days/31):7+Math.floor((days-186)/30);
            let jd=days<186?1+days%31:1+(days-186)%30;
            return {jy,jm,jd};
        },
        toGregorian(jy,jm,jd){
            jy+=1595;
            let days=-355668+365*jy+Math.floor(jy/33)*8+Math.floor(((jy%33)+3)/4);
            days+=jm<7?(jm-1)*31:(jm-7)*30+186;
            days+=jd;
            let gy=400*Math.floor(days/146097);
            days%=146097;
            if(days>36524){gy+=100*Math.floor(--days/36524);days%=36524;if(days>=365)days++;}
            gy+=4*Math.floor(days/1461);
            days%=1461;
            if(days>365){gy+=Math.floor((days-1)/365);days=(days-1)%365;}
            const g_d_m=[0,31,(gy%4===0?29:28),31,30,31,30,31,31,30,31,30,31];
            let gm=1;
            for(;gm<=12&&days>=g_d_m[gm];gm++)days-=g_d_m[gm];
            let gd=days+1;
            return {gy,gm,gd};
        }
    }
};