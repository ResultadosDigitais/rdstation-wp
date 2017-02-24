=== Integração RD Station ===
Contributors: Filipe Mateus do Nascimento
Donate link: -
Tags: integrations, forms, contact form, rd station, resultados digitais

Requires at least: 3.0
Tested up to: 4.6
Stable tag: 4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integre seus formulários de contato com o RD Station.

== Description ==

É muito comum os usuários do RD Station possuírem sites ou blogs em WordPress. É comum também que estes usuários queiram realizar a integração dos formulários de seus sites com o RD Station, convertendo visitantes em leads.

Este plugin facilita essa integração, possibilitando que ela seja feita apenas com o preenchimento de um formulário.

Compatível com:

* Contact Form 7
* Gravity Forms

Features:

*   Permite integrar um único formulário com diferentes contas do RD Station. Ideal para realizar campanhas com parceiros.
*   Criação de um identificador para cada integração, para identificar o formulário de origem de cada lead.
*   Integre quantos formulários você quiser.
*   Testado com última versão do WordPress.

== Installation ==

Recomendamos que você instale este plugin diretamente pelo seu painel do WordPress.
Caso preferir, faça o download e instale o arquivo ZIP em seu site.

Para informações sobre a configuração do plugin, entre neste link: [https://github.com/ResultadosDigitais/rdstation-wp](https://github.com/ResultadosDigitais/rdstation-wp)

== Frequently Asked Questions ==

= É possível integrar com outros plugins, além do Contact Form 7 e Gravity Forms? =

Ainda não é possível. Estamos estudando a possibilidade de inserir a integração com outros plugins de formulários populares.

= Como faço para contribuir com o projeto? =

Este é o nosso repositório oficial no GitHub. Você pode contribuir abrindo issues e pull requests no repositório. Se sua contribuição for importante e relevante, iremos adicioná-la no plugin.

https://github.com/ResultadosDigitais/rdstation-wp

== Screenshots ==

1. Contact Form 7
2. Gravity Forms

== Changelog ==

= 2.4 =

* Adiciona área para inserção de scripts personalizados do RD Station

= 2.3.1 =

* Ignorando campos do Google Captcha

= 2.3 =

* Atualiza a URL de POST do RD Station
* Melhora o mapeamento de campo de email para o Contact Form 7

= 2.2 =

* Verifica a existência de um "post meta" antes de salvá-lo. Se o usuário estiver com o debugger habilitado em produção, alguns erros apareciam ao salvar o formulário de contato.

= 2.0 =
* Code refactor e novas funcionalidades: mapeamento de campos na integração com Gravity Forms. Agora também é possível definir o título do post como identificador da conversão nos formulários.

= 1.1 =
* Permite enviar um novo campo para o formulário, chamado "form_origem".

= 1.0 =
* Gravity Forms and Contact Form 7 support.

== Upgrade Notice ==

= 1.1 =
* Permite enviar um novo campo para o formulário, chamado "form_origem".

= 1.0 =
* Gravity Forms and Contact Form 7 support.
