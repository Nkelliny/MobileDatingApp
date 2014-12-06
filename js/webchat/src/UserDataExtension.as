package {

	

	import flash.xml.XMLNode;

	import org.igniterealtime.xiff.data.Extension;

	import org.igniterealtime.xiff.data.IExtension;

	import org.igniterealtime.xiff.data.ISerializable;

	import org.igniterealtime.xiff.data.ExtensionClassRegistry;

	

	public class UserDataExtension extends Extension implements IExtension, ISerializable {

		

		private var data:UserDataGrabber;

		private var _uid:String;

		private var _firstName:String;

		private var _lastName:String;

		private var _username:String;

		private var _country:String;

		private var _statusMessage;

		private var _isDeserialized:Boolean;

		

		public static const NS:String = "mycontentsite:xmpp:extensions:userdata";

		public static const ELEMENT_NAME = "userData";

		

		public function UserDataExtension( parent:XMLNode = null, userData:UserDataGrabber = null ) {

			

			super( parent );

			

			if ( userData ) data = userData;

		}

		

		private function generateNode( nodeName:String, nodeValue:String = null, attributes:Object = null ):XMLNode {

			

			var nameNode:XMLNode = new XMLNode( 1, nodeName );

			var valueNode:XMLNode;

			

			if ( nodeValue ) {

				

				valueNode = new XMLNode( 3, nodeValue );

				nameNode.appendChild( valueNode );

			}

			

			if ( attributes ) {

				

				nameNode.attributes = attributes;

			}

			

			return nameNode;

		}

		

		public static function enable():void {

			

			ExtensionClassRegistry.register( UserDataExtension );

		}

		

		public function serialize( parent:XMLNode ):Boolean {

			

			var node:XMLNode = this.getNode();

			

			if ( node.parentNode != parent ) {

				

				parent.appendChild( node );

			}

			

			var attributes:Object = {};

			attributes.xmlns = NS;

			attributes.id = data.uid

			var firstNode:XMLNode = generateNode( "firstName", data.firstName );

			var lastNode:XMLNode = generateNode( "lastName", data.lastName );

			var userNode:XMLNode = generateNode( "username", data.username );

			var countryNode:XMLNode = generateNode( "country", data.country );

			var statusNode:XMLNode = generateNode( "statusMessage", data.statusMessage );

			var mainNode:XMLNode = generateNode( "userData", null, attributes );

			mainNode.appendChild( firstNode );

			mainNode.appendChild( lastNode );

			mainNode.appendChild( userNode );

			mainNode.appendChild( countryNode );

			mainNode.appendChild( statusNode );

			setNode( mainNode );

			

			return true;

		}

		

		public function deserialize( node:XMLNode ):Boolean {

			

			if ( node.nodeName == ELEMENT_NAME && node.attributes.hasOwnProperty( "xmlns" ) && node.attributes.xmlns == NS ) {

				

				if ( node.attributes.hasOwnProperty( "id" ) ) {

					

					_uid = node.attributes.id;

				}

				else {

					trace("invalid node xmlns: " + node.nodeName );

					return false;

				}

				

				for each( var child:XMLNode in node.childNodes ) {

					

					switch ( child.nodeName ) {

						

						case "firstName" :

							var first:XML = new XML( child.toString() );

							_firstName = first;

							break;

						case "lastName" :

							var last:XML = new XML( child.toString() );

							_lastName = last;

							break;

						case "username" :

							var user:XML = new XML( child.toString() );

							_username = user;

							break;

						case "country" :

							var c:XML = new XML( child.toString() );

							_country = c;

							break;

						case "statusMessage" :

							var msg:XML = new XML( child.toString() );

							_statusMessage = msg;

							break;

						default :

							trace("invalid node child: " + node.nodeName );

							return false;

					}

				}

				

				if ( _firstName && _lastName && _username && _country && _statusMessage ) {	

					

					// Notify the UserDataExtensionManager Class

					setNode ( node );

					_isDeserialized = true;

					UserDataExtensionManager.registerData( this );

					return true;

				}

				else {

					

					trace("invalid missing data: " + node.nodeName );

					var a:Array = [firstName, lastName, username, country, statusMessage];

					for each(var el:* in a) {

						

						trace(el);

					}

					return false;

				}

			}

			

			return false;

		}

		

		public function getNS():String {

			

			return NS;

		}

		

		public function getElementName():String {

			

			return ELEMENT_NAME;

		}

		

		public function get uid():String {

			

			if ( data ) {

				

				return data.uid;

			}

			else {

				

				return _uid;

			}

		}

		

		public function get firstName():String {

			

			if ( data ) {

				

				return data.firstName;

			}

			else {

				

				return _firstName;

			}

		}

		

		public function get lastName():String {

			

			if ( data ) {

				

				return data.lastName;

			}

			else {

				

				return _lastName;

			}

		}

		

		public function get username():String {

			

			if ( data ) {

				

				return data.username;

			}

			else {

				

				return _username;

			}

		}

		

		public function get country():String {

			

			if ( data ) {

				

				return data.country;

			}

			else {

				

				return _country;

			}

		}

		

		public function get statusMessage():String {

			

			if ( data ) {

				

				return data.statusMessage;

			}

			else {

				

				return _statusMessage;

			}

		}

		

		public function get isDeserialized():Boolean {

			

			return _isDeserialized;

		}

	}

}

