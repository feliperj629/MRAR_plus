# MRAR+ / MRAR_plus - Atualizado para rodar com o PHP7

Algoritmo de Mineração de Regras de Associação Multirrelação em datasets na Web de Dados.

Esse algoritmo foi desenvolvido como produto da seguinte dissertação de mestrado: Mineração de Regras de Associação de Multirrelação em datasets na Web de Dados, 2018, disponível em (http://www.comp.ime.eb.br/pos/?l=0&p=29&q=2018_3). Uma cópia em PDF está disponível dentro da pasta docs.

Artigos científicos foram publicados com informações relacionadas a esse trabalho.
Segue abaixo uma lista dos trabalhos publicados:

1-
OLIVEIRA, F. A.; COSTA, R. L.; GOLDSCHMIDT, R. R. ; CAVALCANTI, M. C. Mineração de Regras de Associação Multirrelação em Grafos: Direcionando o Processo de Busca. In: SIMPÓSIO BRASILEIRO DE BANCO DE DADOS, 32., SESSÃO TÉCNICA SBBD, 9., 2017. Electronic proceedings... Uberlândia: Simpósio Brasileiro de Banco de Dados, 2017, p. 270–275. Disponível em: http://www.facom.ufu.br/~humberto/sbbd2017/wp-content/uploads/sites/3/2017/10/proceedings-sbbd-2017.pdf. Acesso em: 11/10/2018.

2-
OLIVEIRA, F. A.; MARTINS, Y. C.; ROCHA, D. S. B.; SIQUEIRA, M. F.; SILVA, L. A. E.; COSTA, R. L.; GOLDSCHMIDT, R. ; CAVALCANTI, M. C. Jabotg: Extending the herbarium dataset frontiers. In: INTERNATIONAL CONFERENCE ON METADATA AND SEMANTICS RESEARCH (MTSR’17), 11th., 2017. Electronic proceedings... Tallinn: Book of Abstracts and Posters, 2017, p. 45–53. Disponível em: http://www.mtsr-conf.org/2017/archived/files/MTSR17_BOOK_OF_ABSTRACTS_AND_POSTERS_v5_final.pdf. Acesso em: 11/10/2018.

3- 
DE OLIVEIRA, FELIPE ALVES; COSTA, RAQUEL LOPES ; GOLDSCHMIDT, RONALDO R. ; CAVALCANTI, MARIA CLÁUDIA . Multirelation Association Rule Mining on Datasets of the Web of Data. In: the XV Brazilian Symposium, 2019, Aracaju. Proceedings of the XV Brazilian Symposium on Information Systems - SBSI'19. New York: ACM Press, 2019. v. 15. p. 1-61. Disponível em: https://dl.acm.org/citation.cfm?doid=3330204.3330271. Acessado em 10/10/2019.


#Configuração da máquina utilizada nos testes:

Processador - Intel I7 com 8gb de memória.


#No Windows

Passos:

1- Faça o download do PHP para configura-lo no Windows
2- Use o servidor embutido do PHP.
3- Clone este repositório e o instale na pasta raiz do sismtema, ex. "C:\MRAR_plus". 

Obs. Em caso de dúvidas sobre como executar o php no Windows e usar o servidor web embutido, basta consultar um tutórial online. Ex.(https://blog.schoolofnet.com/como-instalar-o-php-no-windows-do-jeito-certo-e-usar-o-servidor-embutido/).

Ideal: Criar uma pasta e instalar o php na raiz do sistema. Ex. "C:\php";
Baixar os arquivos do MRAR_plus também na raiz do sistema. Ex. "C:\MRAR_plus";

Em seguida basta acessar a pasta onde está o MRAR+, verificar se o sistema está reconhecendo os comendos do php e startar o servidor php.

Comandos:

cd \MRAR_plus\

php -version

php -S localhost:8000

Se tudo estiver ok basta o acessar o link do localhost (http://localhost:8000).


#No Linux

Passos:

1- Instalar os programas apache 2 e PHP7:

Exemplo de tutorial online para o UBUNTU 18 "https://www.digitalocean.com/community/tutorials/como-instalar-a-pilha-linux-apache-mysql-php-lamp-no-ubuntu-18-04-pt".

2- Baixar o projeto dentro da pasta raiz do servidor web Ex. "/var/www/".

3- Se o servidor web local já estiver rodando, basta abrir o navegador com o endereço "http://localhost:[PORTA]/[Pasta do Projeto]/index.php" ex: http://localhost:8000/MRAR_plus/index.php 


#Recomendações:

Utilizar o PHP 7.3 ou superior, disponível no site https://www.php.net/downloads.php;

#Em caso de erro "HTTP ERROR 500" ajuste a configuração no arquivo php.ini:
Esses ajustes são necessários para quando o algorítimo for executado com datasets grandes.
Esses valores podem ser alterados conforme a necessidade.

max_execution_time = 120

max_input_time = 120

memory_limit = 512M
