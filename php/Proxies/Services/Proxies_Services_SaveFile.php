<?php
/*
 * Copyright (c) 2014 - Copyright holders CIRSFID and Department of
 * Computer Science and Engineering of the University of Bologna
 * 
 * Authors: 
 * Monica Palmirani – CIRSFID of the University of Bologna
 * Fabio Vitali – Department of Computer Science and Engineering of the University of Bologna
 * Luca Cervone – CIRSFID of the University of Bologna
 * 
 * Permission is hereby granted to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The Software can be used by anyone for purposes without commercial gain,
 * including scientific, individual, and charity purposes. If it is used
 * for purposes having commercial gains, an agreement with the copyright
 * holders is required. The above copyright notice and this permission
 * notice shall be included in all copies or substantial portions of the
 * Software.
 * 
 * Except as contained in this notice, the name(s) of the above copyright
 * holders and authors shall not be used in advertising or otherwise to
 * promote the sale, use or other dealings in this Software without prior
 * written authorization.
 * 
 * The end-user documentation included with the redistribution, if any,
 * must include the following acknowledgment: "This product includes
 * software developed by University of Bologna (CIRSFID and Department of
 * Computer Science and Engineering) and its authors (Monica Palmirani, 
 * Fabio Vitali, Luca Cervone)", in the same place and form as other
 * third-party acknowledgments. Alternatively, this acknowledgment may
 * appear in the software itself, in the same form and location as other
 * such third-party acknowledgments.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
 
	class Proxies_Services_SaveFile implements Proxies_Services_Interface {
		
		private $_params;
		
		/**
		 * The constructor of the service
		 * @return the service object
		 * @param Array the params that are passed to the service
		 */
		public function __construct($params) {
			
			$this->_content = stripcslashes($params['fileContent']);
            unset($params['fileContent']);
			
			$this->_metadata = $params['metadata'];
            unset($params['metadata']);
			
			$this->_params = $params;
		}
		
		/**
		 * this method is used to retrive the result by the service
		 * @return The result that the service computed
		*/ 
		public function getResults() {	
			$this->_params['savetype'] = SAVE_TYPE;
			$this->_params['existUsersGroup'] = EXIST_USERS_GROUP;
			$this->_params['usersDocumentsDir'] = EXIST_USERS_DOCUMENTS_COLLECTION;
			
			// create the post parameters                          
            $postParams = array(
                'content' => $this->_content,
                'metadata' => $this->_metadata,
            );
			
			// TODO: remove this, is temporary trick
			if(strpos($this->_params['file'], "/diff/")) {
				$this->_params['file'] = "";
			}
			
			$credential = EXIST_ADMIN_USER .':'. EXIST_ADMIN_PASSWORD;
			require_once(dirname(__FILE__) . './../../dbInterface/class.dbInterface.php');
			$DBInterface = new DBInterface(EXIST_URL,$credential);
			return XMLToJSON($DBInterface->save_document_html($this->_params,$postParams));
		}
	}