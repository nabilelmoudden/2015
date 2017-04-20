 var app, list;

  list = [
    {
      name: 'Ecart de shoot',
      
      children: [
        {
          name: '20-04-2017',
          children: [
            {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Leader',
			  Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Senior F2E',
			  			  Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Junior F2E',
			  			  Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }
          ]
        }, {
          name: '21-04_2017',
          children: [
            {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Leader',
			  Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Intern'
            }
          ]
        }
      ]
    }, {
      name: 'Ecart de laivrasion',
      opened: true,
      children: [
        {
          name: '20-04-2017',
          children: [
            {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Leader',
			  Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Senior F2E'
            }, {
              name: 'Jason',
              title: 'Junior F2E'
            }
          ]
        }, {
          name: '21-04_2017',
          children: [
            {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Leader',
			   Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Intern',
			  Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }
          ]
        }
      ]
    }, {
      name: 'Ecart de shoot par grille',
      
      children: [
        {
          name: '20-04-2017',
          children: [
            {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Leader'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Senior F2E'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Junior F2E'
            }
          ]
        }, {
          name: '21-04_2017',
          children: [
            {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Leader',
			   Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }, {
              name: 'mlfr/fid01/P1/LDV/TH01',
              title: 'Intern',
			   Action :'Resolu',
			  Responsable:'Nabil',
			  Commantaire:'Rien a faite'
            }
          ]
        }
      ]
    }
  ]; 
app = angular.module('testApp', []).controller('treeTable', [
    '$scope', '$filter', function($scope, $filter) {
      $scope.list = list;
      $scope.toggleAllCheckboxes = function($event) {
        var i, item, len, ref, results, selected;
        selected = $event.target.checked;
        ref = $scope.list;
        results = [];
        for (i = 0, len = ref.length; i < len; i++) {if (window.CP.shouldStopExecution(1)){break;}
          item = ref[i];
          item.selected = selected;
          if (item.children != null) {
            results.push($scope.$broadcast('changeChildren', item));
          } else {
            results.push(void 0);
          }
        }
window.CP.exitedLoop(1);

        return results;
      };
      $scope.initCheckbox = function(item, parentItem) {
        return item.selected = parentItem && parentItem.selected || item.selected || false;
      };
      $scope.toggleCheckbox = function(item, parentScope) {
        if (item.children != null) {
          $scope.$broadcast('changeChildren', item);
        }
        if (parentScope.item != null) {
          return $scope.$emit('changeParent', parentScope);
        }
      };
      $scope.$on('changeChildren', function(event, parentItem) {
        var child, i, len, ref, results;
        ref = parentItem.children;
        results = [];
        for (i = 0, len = ref.length; i < len; i++) {if (window.CP.shouldStopExecution(2)){break;}
          child = ref[i];
          child.selected = parentItem.selected;
          if (child.children != null) {
            results.push($scope.$broadcast('changeChildren', child));
          } else {
            results.push(void 0);
          }
        }
window.CP.exitedLoop(2);

        return results;
      });
      return $scope.$on('changeParent', function(event, parentScope) {
        var children;
        children = parentScope.item.children;
        parentScope.item.selected = $filter('selected')(children).length === children.length;
        parentScope = parentScope.$parent.$parent;
        if (parentScope.item != null) {
          return $scope.$broadcast('changeParent', parentScope);
        }
      });
    }
  ]);

  app.filter('selected', [
    '$filter', function($filter) {
      return function(files) {
        return $filter('filter')(files, {
          selected: true
        });
      };
    }
  ]);