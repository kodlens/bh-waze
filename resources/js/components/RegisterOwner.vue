<template>
  <div>
      <form @submit.prevent="submit">
          <h1 class="title is-5">
              LANDOWNER REGISTRATION
          </h1>
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
                      <b-input v-model="fields.lname" placeholder="Last Name" type="text"></b-input>
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
                      <b-input placeholder="Middle Name" v-model="fields.suffix" type="text"></b-input>
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
                  <b-field label="Upload Business permit"
                           :type="this.errors.business_permit_img ? 'is-danger':''"
                           :message="this.errors.business_permit_img ? this.errors.business_permit_img[0] : ''">
                      <b-field class="file is-primary" :class="{'has-name': !!fields.business_permit_img}">
                          <b-upload v-model="fields.business_permit_img" class="file-label">
                              <span class="file-cta">
                                <b-icon class="file-icon" icon="upload"></b-icon><span class="file-label">Click to upload</span>
                              </span>
                              <span class="file-name" v-if="fields.business_permit_img">
                                {{ fields.business_permit_img.name }}
                              </span>

                          </b-upload>
                      </b-field>
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
            fields: {
                usernamew: '',
                password: '',
                password_confirmation: '',
                lname: '', fname: '', mname: '',
                suffix: '', sex: '', email: '',
                contact_no : '', business_permit_img: null,
                province: '', city: '', barangay: '', street: '',

            },
            errors: {},


            provinces: [],
            cities: [],
            barangays: [],
        }
    },

    methods: {
        submit: function(){
            this.btnClass['is-loading'] = true;
            this.fields.role = 'LANDOWNER';


            var formData = new FormData();
            formData.append('username', this.fields.username);
            formData.append('password', this.fields.password);
            formData.append('password_confirmation', this.fields.password_confirmation);
            formData.append('lname', this.fields.lname);
            formData.append('fname', this.fields.fname);
            formData.append('mname', this.fields.mname);
            formData.append('suffix', this.fields.suffix);
            formData.append('sex', this.fields.sex);
            formData.append('email', this.fields.email);
            formData.append('contact_no', this.fields.contact_no);
            formData.append('business_permit_img', this.fields.business_permit_img);
            formData.append('province', this.fields.province);
            formData.append('city', this.fields.city);
            formData.append('barangay', this.fields.barangay);
            formData.append('street', this.fields.street);
            formData.append('role', this.fields.role);


            axios.post('/register-landowner', formData).then(res=>{
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
