<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$belongsTo = $this->Bake->aliasExtractor($modelObj, 'BelongsTo');
$belongsToMany = $this->Bake->aliasExtractor($modelObj, 'BelongsToMany');
$compact = ["'" . $singularName . "'"];
%>

    /**
     * Edit method
     *
     * @param string|null $id <%= $singularHumanName %> id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $<%= $singularName %> = $this-><%= $currentModelName %>->get($id, [
            'contain' => [<%= $this->Bake->stringifyList($belongsToMany, ['indent' => false]) %>]
        ]);
        if ($this->request->is('put')) {
            // Obtengo los errores de validación
            $errors = $this-><%= $currentModelName %>->validator()->errors($this->request->data);
            $<%= $singularName %> = $this-><%= $currentModelName %>->patchEntity($<%= $singularName %>, $this->request->data);
            if ($this-><%= $currentModelName; %>->save($<%= $singularName %>)) {
                $response = ['result' => 'pass'];
            } else {
                $response = ['result' => 'error'];
                $response['error'] = $errors;
            }
        }else{
            throw new BadRequestException('Error en request');
        }
<%
        foreach (array_merge($belongsTo, $belongsToMany) as $assoc):
            $association = $modelObj->association($assoc);
            $otherName = $association->target()->alias();
            $otherPlural = $this->_variableName($otherName);
%>
        $<%= $otherPlural %> = $this-><%= $currentModelName %>-><%= $otherName %>->find('list', ['limit' => 200]);
<%
            $compact[] = "'$otherPlural'";
        endforeach;
%>
        //$this->set(compact(<%= join(', ', $compact) %>));
        //$this->set('_serialize', ['<%=$singularName%>']);

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
