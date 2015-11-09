<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'authorize' => ['Controller'],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ]
        ]);

        // Allow the display action so our pages controller
        // continues to work.
        $this->Auth->allow(['display']);
    }
    
    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        
        // if the user wants to go to a controller index he has to be a member of the current project
        if ($this->request->action === 'index' || $this->request->action === 'view') {
            $members = TableRegistry::get('Members');
            
            $query = $members
                ->find()
                ->select(['project_id'])
                ->where(['user_id =' => $user['id']])
                ->toArray();
            
            $project_id = $this->request->session()->read('selected_project')['id'];

            foreach($query as $temp){
                if($temp->project_id == $project_id){
                    return True;
                }
            }
        }
        
        // if the user wants to add, edit, delete, upload or addmultiple he has to be a manager or supervisor
        // all operations that all managers and supervisors can do but developers cant should be here
        if ($this->request->action === 'add' || $this->request->action === 'edit'
            || $this->request->action === 'delete' || $this->request->action === 'upload'
            || $this->request->action === 'addmultiple') 
        {
            $members = TableRegistry::get('Members');
            
            $query = $members
                ->find()
                ->select(['project_id', 'project_role'])
                ->where(['user_id =' => $user['id']])
                ->toArray();
            
            $project_id = $this->request->session()->read('selected_project')['id'];

            foreach($query as $temp){
                if($temp->project_id == $project_id && ($temp->project_role == "manager" || $temp->project_role == "supervisor")){
                    return True;
                }
            }
        }
        
        // Default deny
        return false;
    }
    
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
}
