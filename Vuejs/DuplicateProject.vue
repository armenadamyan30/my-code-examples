<template>
  <div class="media align-items-center" v-on:click="duplicateProject(currentProject)">
    <div class="mr-3">
      <div class="ex-duplicate"></div>
    </div>
    <div class="media-body">
      Duplicate project
    </div>
  </div>
</template>
<script>
    export default {
        name: 'duplicate-project',
        props: ['currentProject', 'updateProjects'],
        data() {
            return {

            }
        },
        methods: {
            duplicateProject (project) {
                const self = this;
                let _buildingType = null;
                let buildingTypeId = project.building_types.values().next().value.id;
                this.getBuildingType(buildingTypeId).then((buildingType) => {
                    _buildingType = buildingType;
                    return self.copyProjectThumbnail(project);
                }).then((thumbnail) => {
                    let newProject = DB["ecoglobe.project.NewBuildingProject"].new();
                    newProject.name = 'Copy of ' + project.name;
                    newProject.geo_location = new DB.GeoPoint(project.geo_location.latitude, project.geo_location.longitude);
                    newProject.thumbnail = thumbnail;
                    newProject.owner = DB.User.me;
                    newProject.building_types = new DB.Set();
                    newProject.building_types.add(_buildingType);
                    newProject.insert().then(response => {
                        self.updateProjects(newProject);
                    });
                }).catch(error => {
                    alert(error);
                    // Handle errors of getBuildingType, copyProjectThumbnail
                });

            },
            getBuildingType(buildingTypeId) {
                return new Promise(
                    (resolve, reject) => {
                        DB['ecoglobe.project.BuildingTypeOption'].find().equal('id', buildingTypeId).singleResult((building_type) => {
                            resolve(building_type);
                        });
                    });
            },
            copyProjectThumbnail(project){
                return new Promise(
                    (resolve, reject) => {
                        let thumbnail = null;
                        if(project.thumbnail && project.thumbnail.name && false) { // TODO Need to check
                            let splitName = project.thumbnail.name.split('_');
                            let newName = '';
                            if (splitName.length > 1) {
                                splitName[0] = Date.now();
                            }
                            newName = splitName.join('_');
//                        console.log(newName);
                            let originalFile = new DB.File({name: project.thumbnail.key, type: 'data-url'});
                            //                    console.log(originalFile);
//                        originalFile.download((data) => { // TODO Need to check
//                            // Data is now a data URL string
//                            console.log(data); // "data:image/jpeg;base64,R0lGODlhDAA..."
//                            reject('Error ');
//                        });
                        }else{
                            resolve(thumbnail);
//                            reject('Error ');
                        }
                    });
            },
        },
    }
</script>
