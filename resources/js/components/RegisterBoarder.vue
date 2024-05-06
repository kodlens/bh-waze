<template>
  <div>

      <h1 class="title is-5">
          BOARDER REGISTRATION
      </h1>
      <form @submit.prevent="submit">

          <div class="columns">
              <div class="column">
                  <b-field label="Username"
                           :type="this.errors.username ? 'is-danger':''"
                           :message="this.errors.username ? this.errors.username[0] : ''">
                      <b-input v-model="fields.username" type="text" icon="account"></b-input>
                  </b-field>
              </div>
          </div>

          <div class="columns">
              <div class="column">
                  <b-field label="Password"
                           :type="this.errors.password ? 'is-danger':''"
                           :message="this.errors.password ? this.errors.password[0] : ''">
                      <b-input type="password" v-model="fields.password" icon="lock" password-reveal></b-input>
                  </b-field>
              </div>
              <div class="column">
                  <b-field label="Re-type Password">
                      <b-input type="password" v-model="fields.password_confirmation" password-reveal></b-input>
                  </b-field>
              </div>
          </div>

          <div class="columns">

          </div>

          <div class="columns">
              <div class="column">
                  <b-field label="Last Name"
                           :type="this.errors.lname ? 'is-danger':''"
                           :message="this.errors.lname ? this.errors.lname[0] : ''">
                      <b-input v-model="fields.lname" placeholder="Last Name" @input="capitalFirstLetter" type="text"></b-input>
                  </b-field>
              </div>
              <div class="column">
                  <b-field label="First Name"
                           :type="this.errors.fname ? 'is-danger':''"
                           :message="this.errors.fname ? this.errors.fname[0] : ''">
                      <b-input v-model="fields.fname" placeholder="First Name" type="text"></b-input>
                  </b-field>
              </div>
          </div>


          <div class="columns">
              <div class="column">
                  <b-field label="Middle Name">
                      <b-input placeholder="Middle Name" v-model="fields.mname" type="text"></b-input>
                  </b-field>
              </div>
              <div class="column">
                  <b-field label="Suffix">
                      <b-input placeholder="Suffix" v-model="fields.suffix" type="text"></b-input>
                  </b-field>
              </div>
          </div>

          <div class="columns">
              <div class="column">
                  <b-field label="Sex" expanded
                           :type="this.errors.sex ? 'is-danger':''"
                           :message="this.errors.sex ? this.errors.sex[0] : ''">
                      <b-select placeholder="Sex" v-model="fields.sex" icon="account" expanded>
                          <option value="MALE">MALE</option>
                          <option value="FEMALE">FEMALE</option>
                      </b-select>
                  </b-field>
              </div>
              <div class="column">
                  <b-field label="Email"
                           :type="this.errors.email ? 'is-danger':''"
                           :message="this.errors.email ? this.errors.email[0] : ''">
                      <b-input v-model="fields.email" placeholder="E-mail" type="email"></b-input>
                  </b-field>
              </div>
          </div>

          <div class="columns">
              <div class="column">
                  <b-field label="Contact No"
                           :type="this.errors.contact_no ? 'is-danger':''"
                           :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                      <b-input type="text" placeholder="Contact No (+639481234123)" @input="checkMobileNo" v-model="fields.contact_no"></b-input>
                  </b-field>
              </div>
          </div>


          <div class="columns">
              <div class="column">
                  <b-field label="Guardian Name" expanded
                           :type="this.errors.guardian_name ? 'is-danger':''"
                           :message="this.errors.guardian_name ? this.errors.guardian_name[0] : ''">
                      <b-input type="text" v-model="fields.guardian_name" placeholder="Guardian Name"></b-input>
                  </b-field>
              </div>
              <div class="column">
                  <b-field label="Guardian Contact No" expanded
                           :type="this.errors.guardian_contact_no ? 'is-danger':''"
                           :message="this.errors.guardian_contact_no ? this.errors.guardian_contact_no[0] : ''">
                      <b-input type="text" v-model="fields.guardian_contact_no" placeholder="Guardian Contact No"></b-input>
                  </b-field>
              </div>
          </div>

          <div class="columns">
              <div class="column">
                  <b-field label="Guardian Address" expanded
                           :type="this.errors.guardian_address ? 'is-danger':''"
                           :message="this.errors.guardian_address ? this.errors.guardian_address[0] : ''">
                      <b-input type="text" v-model="fields.guardian_address" placeholder="Guardian Address"></b-input>
                  </b-field>
              </div>
          </div>


          <hr>
          <div class="columns">
              <div class="column">
                  <b-field label="Province" expanded
                           :type="this.errors.province ? 'is-danger':''"
                           :message="this.errors.province ? this.errors.province[0] : ''">
                      <b-select v-model="fields.province" @input="loadCity" expanded>
                          <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                      </b-select>
                  </b-field>
              </div>

              <div class="column">
                  <b-field label="City" expanded
                           :type="this.errors.city ? 'is-danger':''"
                           :message="this.errors.city ? this.errors.city[0] : ''">
                      <b-select v-model="fields.city" @input="loadBarangay" expanded>
                          <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                      </b-select>
                  </b-field>
              </div>
          </div>


          <div class="columns">
              <div class="column">
                  <b-field label="Barangay" expanded
                           :type="this.errors.barangay ? 'is-danger':''"
                           :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                      <b-select v-model="fields.barangay" expanded>
                          <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                      </b-select>
                  </b-field>
              </div>
              <div class="column">
                  <b-field label="Street">
                      <b-input v-model="fields.street"
                               placeholder="Street">
                      </b-input>
                  </b-field>
              </div>
          </div>


          <div class="buttons is-right">
              <button :class="btnClass" >Register</button>
          </div>

    </form> <!-- form -->
  </div>
</template>

<script>
export default {
    data(){
        return{
            btnClass: {
                'is-primary' : true,
                'button' : true,
                'is-loading' : false,
            },
            fields: {},
            errors: {},


            provinces: [],
            cities: [],
            barangays: [],
        }
    },

    methods: {
        submit: function(){
            this.btnClass['is-loading'] = true;
            this.fields.role = 'BOARDER';
            axios.post('/register-boarder', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'SAVED!',
                        message: 'Account successfully created.',
                        type: 'is-success',
                        onConfirm: ()=> {
                            this.btnClass['is-loading'] = false;
                            window.location = '/';
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
                this.btnClass['is-loading'] = false;

            });
        },

        //ADDRESS
        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },
        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },
        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },

        checkMobileNo(evt){
            //var phoneno = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
            var phoneno = /^(09|\+639)\d{9}$/;
            if(evt.match(phoneno)){
                this.errors.contact_no = false;
            }else{
                this.errors.contact_no = true;
                this.errors.contact_no = ['Invalid mobile number format. Valid format sample is (+639161234123)'];

            }
        }
    },

    mounted(){
        this.loadProvince();
    }
}
</script>

<style>

</style>
