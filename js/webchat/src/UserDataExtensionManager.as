package  {

	

	public class UserDataExtensionManager {



		public static var onUserData:Function;

		

		public static function registerData( data:UserDataExtension ) {

			

			if ( data.isDeserialized ) {

				

				if ( onUserData != null ) {

					

					onUserData( data );

				}

			}

		}

	}

}