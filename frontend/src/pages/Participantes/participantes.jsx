import { useEffect, useState } from 'react'
import style from './participantes.module.css'
import { toast } from 'react-toastify';
import Table from 'react-bootstrap/esm/Table.js';
import api from '../../api/api';
import Button from 'react-bootstrap/esm/Button.js';
import ParticipanteModalNovo from '../../components/modais/participantes/participantesModalNovo.jsx';

const Participantes = () => {
    const [participantes, setParticipantes] = useState([]);
    const [rows, setRows] = useState([])
    const [showModalNovo, setShowModalNovo] = useState(false)
    const [showModalEditar, setShowModalEditar] = useState(false)

    const buscarParticipantes = async () => {
        try {
            const res = await api.get('/participante')


            setParticipantes(res.data.dados)

        } catch (err) {
            toast.error('Erro ao buscar participantes');
        }
    }

    useEffect(() => {
        buscarParticipantes()
        setRows(participantes);
    }, [])

    const columns = [
        { header: 'Nome', accessor: 'nome' },
        { header: 'Cpf', accessor: 'cpf' },
        { header: 'Email', accessor: 'email' },
        { header: 'Telefone', accessor: 'telefone' },
        { header: 'Data de nascimento', accessor: 'data_nascimento' },
    ]

    const enviarDadosNovo = async (dados) => {
        try{
            const res = await api.post('/participante', dados)

            if(res.status === 201){
                toast.success('Participante cadastrado com sucesso!')
            }
        }catch(err){
            toast(err.response.data.mensagem || 'Erro ao tentar cadastrar participante')
        }
    }

    return (
        <>
            <h1 className={style.titulo}>Participantes</h1>
            <Button className={style.button} onClick={() => setShowModalNovo(!showModalNovo)}>Novo</Button>
            <Table responsive bordered hover>
                <thead>
                    <tr>
                        {columns.map(columns => (
                            <th key={columns.accessor}>{columns.header}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {participantes.map(rows => (
                        <tr>
                            {columns.map(columns => (
                                <td key={columns.accessor}>{participantes[columns.accessor]}</td>

                            ))}
                        </tr>
                    ))}
                </tbody>
            </Table>
            <ParticipanteModalNovo handleClose={() => setShowModalNovo(false)} show={showModalNovo} setShow={setShowModalNovo} enviarDados={enviarDadosNovo} dados={participantes}/>
        </>
    )
}

export default Participantes