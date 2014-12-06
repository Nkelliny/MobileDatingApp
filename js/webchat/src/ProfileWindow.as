package {

	

	import flash.display.MovieClip;

	import flash.events.Event;

	import flash.events.MouseEvent;

	import flash.display.DisplayObject;

	

	public class ProfileWindow extends MovieClip {

		

		public static const DESTROYED:String = "destroyed";

		

		public function ProfileWindow() {

			

			super();

			addEventListener( Event.ADDED_TO_STAGE, onAdded );

		}

		

		private function init():void {

			

			txt.selectable = false;

			txt.wordWrap = true;

			closeBtn.label = "Close";

			closeBtn.addEventListener( MouseEvent.CLICK, destroy );

			stage.addEventListener( Event.RESIZE, onStageResize );

			center();

		}

		

		private function center():void {

			

			x = ( stage.stageWidth - width ) / 2;

			y = ( stage.stageHeight - height ) / 2;

		}

		

		private function onAdded( e:Event ):void {

			

			removeEventListener( Event.ADDED_TO_STAGE, onAdded );

			init();

		}

		

		private function onStageResize( e:Event ):void {

			

			center();

		}

		

		private function destroy( e:Event ) {

			

			removeEventListener( MouseEvent.CLICK, destroy );

			stage.removeEventListener( Event.RESIZE, onStageResize );

			

			if ( this.parent ) {

				

				for ( var i:int = 0; i < numChildren; i++ ) {

					

					removeChild( getChildAt( i ) );

				}

				

				this.parent.removeChild( this );

				dispatchEvent( new Event( DESTROYED ) );

			}

		}

		

		public function set text( value:String ):void {

			

			txt.text = value;

		}

	}

}

