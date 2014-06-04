<?php

namespace Cms\Services;

use Cms\Models\Comment,
    Cms\Models\CommentVote,
    DScribe\Core\Engine,
    DScribe\Core\Repository,
    DSLive\Services\SuperService,
    Object;

class CommentService extends SuperService {

    public function voteUp($commentId, $userId) {
        return $this->vote($commentId, $userId);
    }

    public function voteDown($commentId, $userId) {
        return $this->vote($commentId, $userId, false);
    }

    public function vote($commentId, $userId, $up = true) {
        $comment = $this->repository
                ->join('commentVote')
                ->findOne($commentId);

        if (!$comment)
            throw new \Exception('Invalid action');

        $vote = $comment->commentVote(array(
                    'where' => array(array(
                            'user' => $userId,
                        )),
                ))->first();

        if ($vote) { // Vote already exists. Neutralize current user's vote
            if ($vote->getDownVote()) { // Already voted down. Remove
                $comment->voteDown(false);
            }
            else if ($vote->getUpVote()) { // Already voted up. Remove
                $comment->voteUp(false);
            }
        }
        else {
            $vote = new CommentVote;
            $vote->setComment($commentId)->setUser($userId);
        }

        if ($up) {
            $comment->voteUp();
            $vote->voteUp();
        }
        else {
            $comment->voteDown();
            $vote->voteDown();
        }

        $this->save($comment, NULL, false);
        $this->model = $comment;

        $voteRepo = new Repository(new CommentVote);
        if ($voteRepo->upsert(array($vote))->execute()) {
            return $this->flush();
        }

        return false;
    }

    public function create(Comment $model, Object $files = null, $flush = true) {
        $model->setUser(Engine::getUserIdentity()->getId());
        return parent::create($model, $files, $flush);
    }

}
