//just part of whole file

export default {
    getScenarioByFullId: state => id => state.scenarios.find(scenario => scenario.id === id),
};
