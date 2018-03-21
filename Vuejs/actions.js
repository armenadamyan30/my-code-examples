//just part of whole file
export default {
    FETCH_SCENARIOS({ commit, dispatch, state }, payload) {
        return new Promise((resolve, reject) => {
            DB['testTable'].load(payload.id).then((projectData) => {
		    const actions = projectData.scenarios.map(item => item.load());
		    Promise.all(actions).then((scenarios) => {
			commit('SET_SCENARIOS', { scenarios });
			resolve();
		    }).catch((error) => {
			console.error(error);
			reject(error);
		    });
            });
        });
    },
    UPDATE_SCENARIO({ commit, dispatch, state }, payload) {
        payload.scenario.update().then(scenario => commit('UPDATE_SCENARIO', { scenario }));
    },
};
