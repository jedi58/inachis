# Default Apache virtualhost template

<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot {{ apache.docroot }}
    ServerName {{ apache.servername }}

    Redirect / https://{{ apache.servername }}/
</VirtualHost>

<IfModule ssl_module>
    NameVirtualHost *:443

    <VirtualHost *:443>
        ServerAdmin webmaster@localhost
        DocumentRoot {{ apache.docroot }}
        ServerName {{ apache.servername }}

        SSLEngine on
        SSLCertificateFile /etc/ssl/inachis.dev/inachis.dev.csr
        SSLCertificateKeyFile /etc/ssl/inachis.dev/inachis.dev.key
        SSLCACertificateFile /etc/ssl/certs/ca-certificates.crt

        <Directory {{ apache.docroot }}>
            AllowOverride All
            Options -Indexes +FollowSymLinks
            Require all granted
        </Directory>
    </VirtualHost>
</IfModule>