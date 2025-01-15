<h1>KpEsportes</h1>
Esse projeto é um freela fruto de uma parceria entre eu e meu amigo Lucas_Paz, onde eu (Alysson) fiquei responsavel pelo backend da aplicaçao e o lucas ficou responsavel pelo frontend. a aplicaçao é uma loja virtual de calçados KpEsportes, que possui uma loja fisica localizada em Jucás, Carius.

<h1>Database Docs</h1>
<h2>Model Entidade Relacionamento (MER)</h2>
<img src="./docs/db/KpEsportes_DB_MER.png" />
<h2>Modelo Relacional (MR)</h2>
<img src="./docs/db/KpEsportes_DB_MR.png" />

<h1>UML Docs</h1>
<h2>Use Case</h2>
<img src="./docs/uml/Kp_Esportes_UML_UseCase.png" />

<h1>Endpoints</h1>
<h2>Authentication</h2>
<h3>/api/auth/signup/client</h3>
<p>Esta rota server para cadastrar um novo cliente, ela deve ser acessada sem um token de autenticaçao que normalmente fica no header "Authorization", se voce tentar acessar esta rota com um token você recebera uma mensagem de erro, no corpo da requisiçao deve estar presente:</p>
<ul>
    <li>name - é obrigatorio e deve ter entre 3 e 20 caracterse</li>
    <li>email - é obrigatorio, deve ser um email valido, e nao pode ser repedito</li>
    <li>password - é obrigatorio e deve ter entre 8 e 16 caracteres</li>
</ul>