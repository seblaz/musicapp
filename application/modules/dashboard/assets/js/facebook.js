/*globals site_url*/
$(document).ready(function($) {

	$.getScript('//connect.facebook.net/en_US/sdk.js', function(){
		FB.init({
			appId: '1770709086477424',
			version: 'v2.5' // or v2.0, v2.1, v2.2, v2.3
		});
		FB.getLoginStatus(function (response) {
			console.log(response);
			response.status = 's';
			if(response.status == 'connected'){
				FB.api('/me', function(resp){
					$("#navbar-nav>ul").append(
						'<li class="alternate sidepanel-item-small hidden-sm hidden-md hidden-lg"> \
							<a href="/ar/premium/" id="nav-link-upgrade" data-ga-category="menu" data-ga-action="upgrade" style="animation-delay: 85ms;"> \
							Subir de categoría \
							</a> \
						</li> \
						<li class="alternate sidepanel-item-small hidden-sm hidden-md hidden-lg"> \
							<a href="/ar/premium/" id="nav-link-upgrade" data-ga-category="menu" data-ga-action="upgrade" style="animation-delay: 85ms;"> \
							Cuenta \
							</a> \
						</li> \
						<li class="alternate sidepanel-item-small hidden-sm hidden-md hidden-lg"> \
							<a href="/ar/premium/" id="nav-link-upgrade" data-ga-category="menu" data-ga-action="upgrade" style="animation-delay: 85ms;"> \
							Cerrar Sesión \
							</a> \
						</li>'
					);
				});
			}else{
				$("#navbar-nav>ul").append(
					'<li class="alternate sidepanel-item-small " > \
						<a href="/ar/signup/" id="nav-link-sign_up" data-ga-category="menu" data-ga-action="sign_up"> \
						Registráte \
						</a> \
					</li> \
					<li class="alternate sidepanel-item-small"> \
						<a  href="https://www.spotify.com/login?continue=https%3A%2F%2Fwww.spotify.com%2Far%2Faccount%2Foverview%2F"  id="header-login-link" class="user-link "> \
						<span class="user-text navbar-user-text">Iniciar Sesión</span> \
						</a> \
					</li>'
				);
			}
		});
	});
});